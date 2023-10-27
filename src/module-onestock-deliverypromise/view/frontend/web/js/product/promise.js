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
    'mage/translate'
    ], function ($, Component, urlBuilder, ko) {

    "use strict";

    return Component.extend({

        /**
         * Constructor
         */
        initialize: function () {
            this.promises = ko.observable([]);
            this._super();
            $.getJSON(
                urlBuilder.build(`rest/V1/delivery_promises/products/${this.sku}`),
            ).done((response) => {
                if (!response.errors) {
                    response = response
                        .filter((option)=>{
                            return typeof(this.methods[option.delivery_method]) != undefined;
                        })
                        .map((option) => {
                            option.delivery_name = this.methods[option.delivery_method];
                            return option
                        })

                    this.promises(response)
                }
            }).fail(function () {
                
            });
        }
    });
});
