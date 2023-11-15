
var config = {
    map: {
        '*': {
            'Magento_Tax/template/checkout/shipping_method/price.html':
                'Smile_OnestockDeliveryPromise/template/checkout/shipping_method/price.html'
        }
    },
    config: {
        mixins: {
            'Magento_Swatches/js/swatch-renderer': {
                'Smile_OnestockDeliveryPromise/js/product/swatch-renderer': true
            },
            'Magento_Tax/js/view/checkout/shipping_method/price': {
                'Smile_OnestockDeliveryPromise/js/view/checkout/shipping_method/price-mixin': true
            },
            'Magento_Checkout/js/view/cart/shipping-rates': {
                'Smile_OnestockDeliveryPromise/js/view/cart/shipping-rates': true
            }
        }
    }
};
