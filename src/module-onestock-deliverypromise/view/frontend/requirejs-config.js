
var config = {
    map: {
        '*': {
            'Magento_Tax/template/checkout/shipping_method/price.html':
                'Smile_OnestockDeliveryPromise/template/checkout/shipping_method/price.html'
        },
    },
    config: {
        mixins: {
            'Magento_Swatches/js/swatch-renderer': {
                'Smile_OnestockDeliveryPromise/js/product/swatch-renderer': true
            }
        }
    }
};
