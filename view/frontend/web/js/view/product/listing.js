define(['jquery', 'uiComponent', 'ko', 'mage/url', 'mage/storage'],
    function ($, Component, ko, urlBuilder, storage) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Smile_Catalog/product/listing'
            },

            initialize: function (config) {
                this.productCollection = config.productCollection;

                this.customerName = ko.observableArray([]);
                this.customerData = ko.observable('');
                this._super();
            },

            productList: ko.observableArray([]),

            getProductData: function () {
                this.productCollection.forEach(dataParser);

                function dataParser (value, index, array) {
                    var id = value.entity_id;

                    var serviceUrl = urlBuilder.build('catalog/category/product?id='+id);
                    return storage.post(
                        serviceUrl,
                        ''
                    ).done(
                        function (response) {
                            this.productList.push(JSON.parse(response));
                        }
                    ).fail(
                        function (response) {
                            alert(response);
                        }
                    );
                }
            },
        });
    }
);
