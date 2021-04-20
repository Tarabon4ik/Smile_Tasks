define([
    'jquery',
    'underscore',
    'Magento_Ui/js/form/form',
    'ko',
    'Smile_Onepage/js/model/step-navigator',
    'mage/translate'
], function (
    $,
    _,
    Component,
    ko,
    stepNavigator,
    $t
) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Smile_Onepage/place',
        },
        isVisible: ko.observable(true),

        /**
         * @return {exports}
         */
        initialize: function () {
            this._super();

            stepNavigator.registerStep(
                'place',
                'place',
                $t('Place'),
                this.isVisible,
                _.bind(this.navigate, this),
                this.sortOrder
            );

            return this;
        },

        /**
         * Initialize 'isVisible' observable
         */
        initObservable: function () {
            this._super().observe(['isVisible']);

            return this;
        },

        /**
         * Navigator change hash handler.
         *
         * @param {Object} step - navigation step
         */
        navigate: function (step) {
            step && step.isVisible(true);
        },

        /**
         * Place Data Setter
         */
        setPlaceData: function () {
            stepNavigator.next();
        }
    });
});
