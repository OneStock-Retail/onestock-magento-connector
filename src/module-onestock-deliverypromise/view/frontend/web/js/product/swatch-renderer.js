
define([
    'jquery',
    'jquery-ui-modules/widget'
], function ($) {
    'use strict';

    /**
     * There is already a mixin from "magento/module-inventory-catalog-frontend-ui"
     * but this do not prevent it
     * We still can see the ajax request /inventory_catalog/product/getQty/?sku= 
     */
    return function (SwatchRenderer) {
        $.widget('mage.SwatchRenderer', SwatchRenderer, {

            /** @inheritdoc */
            _OnClick: function ($this, widget) {
                var productVariationsSku = this.options.jsonConfig.sku;
                this._super($this, widget);

                if (typeof(productVariationsSku[widget.getProductId()]) != "undefined") {
                    $( document).trigger( "display_product_promise", [ productVariationsSku[widget.getProductId()] ] );
                }
            }
        });

        return $.mage.SwatchRenderer;
    };
});
