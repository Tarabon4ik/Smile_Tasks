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
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        categoryId: config.categoryId,
                    },
                    context: this,
                    showLoader: true,

                    /** @param {Object} response */
                    success: function (response) {
                        this.productList.push.apply(this.productList, response);

                        $('#product-widget').on({
                            mouseenter: function () {
                                $(this).css({border: '0 solid #f37736'}).animate({
                                    borderWidth: 4
                                }, 500);
                            },
                            mouseleave: function () {
                                $(this).animate({
                                    borderWidth: 0
                                }, 500);
                            },
                            click: function () {
                                $('#product-widget').wrap("<a data-bind=\"attr: {href: product.url}\"></a>");
                            }
                        });

                        $("#price").text(function(i, origText){
                            return "$" + origText.slice(0, origText.search(".") - 1) + "," + "0";
                        });
                    }
                });
            }
        });
    }
);
