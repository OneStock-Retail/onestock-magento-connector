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

        /**
         * Constructor
         */
        initialize: function () {
            this._super();
            this.promises = ko.observable([]);
            this.lastCountry = ko.observable("");
            this.lastPostcode = ko.observable("");
            this.selectedCountry = ko.observable(this.getCountry());
            this.selectedPostcode = ko.observable(this.getPostcode());
            this.selectedSku = ko.observable(this.sku);
            this.selectedSku.subscribe(this.displayPromise.bind(this));

            $( document ).on( "update_sku_for_promise", (_, sku) => {
                if (typeof(sku)!="undefined") {
                    this.selectedSku(sku);
                }
            });
            $( document ).on( "rollback_country_for_promise", (_) => {
                this.selectedCountry(this.lastCountry());
                this.selectedPostcode(this.lastPostcode())
            });
            $( document ).on( "update_country_for_promise", (_) => {
                this.setCountry(this.selectedCountry()); 
                this.setPostcode(this.selectedPostcode());
                this.displayPromise();
            });
            this.displayPromise();
        },

        formatCountryName: function (item) {
            return item.label;
        },

        formatCountryCode: function (item) {
            return item.value;
        },

        getCountryLabelFromCode: function (code) {
            for(var i in this.countries) {
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

        getPostcode: function () {
            var address = checkoutData.getShippingAddressFromData();
            var postcode = this.postcode;
            if (typeof (address) != "undefined" && address != null && address.postcode) {
                postcode = address.postcode;
            }
            return postcode;
        },

        setCountry: function (code) {
            var address = checkoutData.getShippingAddressFromData();
            if (typeof (address) == "undefined" || address == null) {
                address = {}
            }
            address.country_id = code;
            checkoutData.setShippingAddressFromData(address);
            
            return this;
        },

        setPostcode: function (code) {
            var address = checkoutData.getShippingAddressFromData();
            if (typeof (address) == "undefined" || address == null) {
                address = {}
            }
            address.postcode = code;
            checkoutData.setShippingAddressFromData(address);
            return this;
        },

        /**
         *
         */
        displayPromise: function () {
            var country_id = this.getCountry()
            var postcode = this.getPostcode()
            var sku = this.selectedSku();

            $.ajax({
                url: urlBuilder.build(`rest/V1/delivery_promises/shipping-methods/${sku}/${country_id}/${postcode}`),
                dataType: 'json',
                cache:true,
                type: 'GET'
            }).done((response) => {
                this.lastCountry(country_id);
                this.lastPostcode(postcode);
                if (!response.errors) {
                    this.promises(response)
                }
            }).fail(function () {

            });
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
