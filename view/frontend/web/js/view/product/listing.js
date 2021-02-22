define(
    ['jquery', 'uiComponent', 'ko'],
    function ($, Component, ko) {
        'use strict';

        return Component.extend({

            initialize: function (config) {
                this._super();

                this.productList = ko.observableArray();

                $.ajax({
                    url: config.requestUrl,
                    dataType: 'json',
                    data: {
                        categoryId: config.categoryId,
                    },
                    context: this,
                    showLoader: true,

                    /** @param {Object} response */
                    success: function (response) {
                        this.productList.push.apply(this.productList, response);
                    }
                });
            }
        });
    }
);
