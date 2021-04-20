define([
    'jquery',
    'Magento_Ui/js/form/form',
    'Magento_Customer/js/action/login',
    'Magento_Customer/js/model/customer',
    'mage/validation',
    'Smile_Onepage/js/model/authentication-messages',
    'Smile_Onepage/js/model/full-screen-loader'
], function ($, Component, loginAction, customer, validation, messageContainer, fullScreenLoader) {
    'use strict';

    var manifestationConfig = window.manifestationConfig;

    return Component.extend({
        isGuestOnepageAllowed: manifestationConfig.isGuestOnepageAllowed,
        isCustomerLoginRequired: manifestationConfig.isCustomerLoginRequired,
        registerUrl: manifestationConfig.registerUrl,
        forgotPasswordUrl: manifestationConfig.forgotPasswordUrl,
        autocomplete: manifestationConfig.autocomplete,
        defaults: {
            template: 'Smile_Onepage/authentication'
        },

        /**
         * Is login form enabled for current customer.
         *
         * @return {Boolean}
         */
        isActive: function () {
            return !customer.isLoggedIn();
        },

        /**
         * Provide login action.
         *
         * @param {HTMLElement} loginForm
         */
        login: function (loginForm) {
            var loginData = {},
                formDataArray = $(loginForm).serializeArray();

            formDataArray.forEach(function (entry) {
                loginData[entry.name] = entry.value;
            });

            if ($(loginForm).validation() &&
                $(loginForm).validation('isValid')
            ) {
                fullScreenLoader.startLoader();
                loginAction(loginData, manifestationConfig.manifestationUrl, undefined, messageContainer).always(function () {
                    fullScreenLoader.stopLoader();
                });
            }
        }
    });
});
