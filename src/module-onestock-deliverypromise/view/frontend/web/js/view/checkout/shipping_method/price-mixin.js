define([
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils'
], function (Component, quote, priceUtils) {
    'use strict';

    return function (Component) {
        return Component.extend({

            /**
             * Return shipping method item price
             * @param {*} price
             * @return {*|String}
             */
            getFormattedPrice: function (price) {
                if (price === 0)
                    return 'Free';

                return priceUtils.formatPrice(price, quote.getPriceFormat());
            }
        });
    }
});

