var config = {
    'config': {
        'mixins': {
            'Magento_Checkout/js/view/shipping': {
                'Smile_Checkout/js/view/shipping-payment-mixin': true
            },
            'Magento_Checkout/js/view/payment': {
                'Smile_Checkout/js/view/shipping-payment-mixin': true
            }
        }
    }
}
