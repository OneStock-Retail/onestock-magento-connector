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
    'Magento_Swatches/js/swatch-renderer',
    'mage/translate',
    ], function ($, Component, urlBuilder, ko, checkoutData, configurable) {

    "use strict";

    return Component.extend({

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
        }
    });
});
