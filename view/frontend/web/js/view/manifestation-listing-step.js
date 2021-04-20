define([
    'ko',
    'uiComponent',
    'underscore',
    'Smile_Onepage/js/model/step-navigator',
    'mage/translate',
    'Magento_Ui/js/modal/prompt',
    'text!Smile_Onepage/template/modal/manifestation-prompt.html',
    'jquery',
    'jquery/ui'
], function (ko, Component, _, stepNavigator, $t, prompt, promptTemplate, $) {
    'use strict';

    $(document).on("click",".product-item-info", function() {
        $('.prompt-modal-content').prompt({
            title: $.mage.__('Manifestation Details'),
            modalClass: 'prompt',
            promptContentTmpl: promptTemplate,
            value: $.mage.__('Value by default'),
            validation: true,
            promptField: '[data-role="promptField"]',
            validationRules: ['required-entry'],
            attributesForm: {
                novalidate: 'novalidate',
                action: ''
            },
            manifestationData: {
                name: 'name',
                'data-validate': '{required:true}',
                maxlength: '255'
            },
            actions: {
                always: function() {
                    // do something when the modal is closed
                },
                confirm: function () {
                    // do something when the confirmation button is clicked
                },
                cancel: function () {
                    // do something when the cancel button is clicked
                }
            }
        });
    });

    return Component.extend({
        defaults: {
            template: 'Smile_Onepage/manifestation-listing',
            manifestationDetailsTemplate: 'Smile_Onepage/manifestation-details/form',
        },

        // add here your logic to display step,
        isVisible: ko.observable(true),

        /**
         * @returns {*}
         */
        initialize: function () {
            this._super();

            this.manifestationList = ko.observableArray();

            // register your step
            stepNavigator.registerStep(
                // code will be used as step content id in the component template
                'manifestation-listing',
                // step alias
                null,
                // step title value
                'Manifestation listing',
                // observable property with logic when display step or hide step
                this.isVisible,
                _.bind(this.navigate, this),
                10
            );

            //ajax request to the controller
            $.ajax({
                url: window.manifestationConfig.manifestation_onepage.manifestationListingController,
                type: 'POST',
                dataType: 'json',
                context: this,
                showLoader: true,

                /** @param {Object} response */
                success: function (response) {
                    this.manifestationList.push.apply(this.manifestationList, response);
                },
            });

            return this;
        },

        /**
         * Navigator change hash handler.
         */
        navigate: function () {
            this.isVisible(true);
        },

        /**
         * @returns void
         */
        navigateToNextStep: function () {
            stepNavigator.next();
        },

        /**
         * Setter for temporary manifestation_id
         */
        setTmpManId: function () {
            this.isVisible(true);
        },

        /**
         * Setter for temporary manifestation_id
         */
        getTmpManId: function () {
            this.manifestationList;
        },
    });
});
