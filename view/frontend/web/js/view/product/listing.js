define(['jquery', 'uiComponent', 'ko', 'mage/url', 'mage/storage'],
    function ($, Component, ko, urlBuilder, storage) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Smile_Catalog/product/listing'
            },

            initialize: function (config) {
                var self = this;
                self._super();

                var productCollection = config.productCollection;

                self.productList = ko.observableArray([]);

                productCollection.forEach(dataParser);

                function dataParser (value, index, array) {
                    var id = value.entity_id;

                    var serviceUrl = urlBuilder.build('catalog/category/product?id='+id);

                    return storage.post(
                        serviceUrl,
                        ''
                    ).done(
                        function (response) {
                            self.productList.push(JSON.parse(response));
                        }
                    ).fail(
                        function (response) {
                            alert(response);
                        }
                    );
                }
            }

        });
    }
);
