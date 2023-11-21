/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 *
 * @category  Smile
 * @package   Smile\OnestockDeliveryPromise
 * @author    Pascal Noisette <Pascal.Noisette@smile.fr>
 * @copyright 2023 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

/*jshint browser:true jquery:true*/
/*global alert*/

define([
    'jquery',
    'uiComponent',
    'mage/url',
    'ko',
    'Magento_Checkout/js/checkout-data',
    'Magento_Catalog/js/price-utils',
    'mage/translate'
], function ($, Component, urlBuilder, ko, checkoutData,priceUtils, $t) {

    "use strict";

    return Component.extend({

        defaults: {
            greenestOption: $t('Greenest option'),
            delivered: $t('Delivered'),
            tomorrow: $t('tomorrow between '),
            today: $t('today between '),
            andDate: $t(' and '),
            onDate: $t('on '),
            byCarrier: $t('by '),
            byDelay: $t('by ordering within '),
            parcelNumber: $t('Your order may arrive in multiple parcels'),
            homeDeliveryTo: $t('Home delivery to'),
        },

        /**
         * Constructor
         */
        initialize: function () {
            this.promises = ko.observable([]);
            this.lastCountry = ko.observable("");
            this.selectedCountry = ko.observable(this.getCountry());
            this.selectedSku = ko.observable(this.sku);
            this.selectedSku.subscribe(this.displayPromise.bind(this));
            this._super();

            $( document ).on( "update_sku_for_promise", (_, sku) => this.selectedSku(sku));
            $( document ).on( "rollback_country_for_promise", (_) => this.selectedCountry(this.lastCountry()));
            $( document ).on( "update_country_for_promise", (_) => this.setCountry(this.selectedCountry()));
            this.displayPromise();
        },

        formatCountryName: function (item) {
            return item.label;
        },

        formatCountryCode: function (item) {
            return item.value;
        },

        getCountryLabelFromCode: function (code) {
            for(i in this.countries) {
                var country = this.countries[i];
                if (this.formatCountryCode(country) == code()) {
                    return ko.observable(this.formatCountryName(country));
                }
            }
            return code;
        },

        getCountry: function () {
            var address = checkoutData.getShippingAddressFromData();
            var country_id = this.country_id;
            if (typeof (address) != "undefined" && address != null && address.country_id) {
                country_id = address.country_id;
            }
            return country_id;
        },

        setCountry: function (code) {
            var address = checkoutData.getShippingAddressFromData();
            if (typeof (address) == "undefined" || address == null) {
                address = {}
            }
            address.country_id = code;
            checkoutData.setShippingAddressFromData(address);
            this.displayPromise();
            return this;
        },

        /**
         *
         */
        displayPromise: function () {
            var country_id = this.getCountry()
            var sku = this.selectedSku();

            $.ajax({
                url: urlBuilder.build(`rest/V1/delivery_promises/shipping-methods/${sku}/${country_id}`),
                dataType: 'json',
                cache:true,
                type: 'GET'
            }).done((response) => {
                this.lastCountry(country_id);
                if (!response.errors) {
                    this.promises(response)
                }
            }).fail(function () {

            });
        },
        /**
         *
         * @param start
         * @param end
         * @returns {string}
         */
        calculDeliveryDate: function (start, end) {
            var locale = window.LOCALE || 'en-US';
            var result= "";
            const timestampNow = Date.now();
            const timestampEtaStart = start * 1000;
            const timestampEtaEnd = end * 1000;
            const dateNow = new Date(timestampNow);
            const dateEtaStart = new Date(timestampEtaStart);
            const dateEtaEnd = new Date(timestampEtaEnd);
            const dateNowDayAfter = new Date(timestampNow + 86400000);
            const dateEtaEndDayAfter = new Date(timestampEtaEnd + 86400000);


            if(( timestampEtaStart - timestampNow ) < 86400000 && ( dateNow.getDay() === dateEtaEnd.getDay())) {
                result += this.today;

                result += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + this.andDate;
                result += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
                return result;
            }
            if(( timestampEtaStart - timestampNow ) < 86400000 && ( dateNow.getDay() !== dateEtaEnd.getDay())) {
                result += this.tomorrow;

                result += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + this.andDate;
                result += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
                return result;
            }
            if(( timestampEtaStart - timestampNow ) < 17280000 && ( dateNowDayAfter.getDay() !== dateEtaEndDayAfter.getDay()))  {
                result += this.tomorrow;
                result += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + this.andDate;
                result += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
                return result;
            }
            result += this.onDate
            var day = function() {
                const options = {
                    weekday: 'long',
                };
                return  dateEtaStart.toLocaleDateString(locale, options);
            }

            var month = function() {
                const options = {
                    month: 'long',
                };
                return  dateEtaStart.toLocaleDateString(locale, options);
            }

            var year = function() {
                const options = {
                    year: '2-digit',
                };
                return  dateEtaStart.toLocaleDateString(locale, options);
            }
            result += day() + ", " + month() + " " + year() + " between "
            result += dateEtaStart.getHours() + ":"  + dateEtaStart.getMinutes() + this.andDate;
            result += dateEtaEnd.getHours() + ":"  + dateEtaEnd.getMinutes();
            return result;
        },

        /**
         *
         * @param timestampCutOff
         * @returns {string}
         * @constructor
         */
        CalculDeadLineText: function (timestampCutOff) {

            var dateCutOff = new Date(timestampCutOff * 1000);
            var timestampNow = Date.now();
            var dateNow = new Date(timestampNow);
            var textToDisplay = "";

            var diff = {}							// Initialisation du retour
            var tmp = dateCutOff - dateNow;
            tmp = Math.floor(tmp / 1000);             // Nombre de secondes entre les 2 dates
            diff.sec = tmp % 60;					// Extraction du nombre de secondes

            tmp = Math.floor((tmp - diff.sec) / 60);	// Nombre de minutes (partie entière)
            diff.min = tmp % 60;					// Extraction du nombre de minutes

            tmp = Math.floor((tmp - diff.min) / 60);	// Nombre d'heures (entières)
            diff.hour = tmp % 24;					// Extraction du nombre d'heures

            tmp = Math.floor((tmp - diff.hour) / 24);	// Nombre de jours restants
            diff.day = tmp;

            if (diff.day) {
                textToDisplay += diff.day + "d " + diff.hour + "h " +  diff.min + "m";
                return textToDisplay;
            }
            if (diff.hour) {
                textToDisplay +=  diff.hour + "h " + diff.min + "m";
                return textToDisplay;
            }
            if (diff.min) {
                textToDisplay +=  diff.min + "m ";
                return textToDisplay;
            }
        },

        /**
         * @param {*} price
         * @return {*|String}
         */
        getFormattedPrice: function (price) {
            return priceUtils.formatPriceLocale(price);
        }

    });
});
