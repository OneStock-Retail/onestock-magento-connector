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
            this._super();

            $( document ).on( "display_product_promise", this.displayPromise.bind(this));
            $( document).trigger( "display_product_promise", [ this.sku ] );
        },

        /**
         * 
         */
        displayPromise: function (_, sku) {

            var address = checkoutData.getShippingAddressFromData();
            var country_id = this.country_id;
            if (typeof (address) != "undefined" && address != null && address.country_id) {
                country_id = address.country_id;
            }

            $.ajax({
                url: urlBuilder.build(`rest/V1/delivery_promises/shipping-methods/${sku}/${country_id}`),
                dataType: 'json',
                cache:true,
                type: 'GET'
            }).done((response) => {
                if (!response.errors) {
                    this.promises(response)
                }
            }).fail(function () {
                
            });
        }
    });
});
