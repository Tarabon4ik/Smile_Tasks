/**
 * Onepage adapter for manifestation data storage
 *
 * @api
 */
define([
    'jquery',
    'Magento_Customer/js/customer-data',
    'jquery/jquery-storageapi'
], function ($, storage) {
    'use strict';

    var cacheKey = 'manifestation-data',

        /**
         * @param {Object} data
         */
        saveData = function (data) {
            storage.set(cacheKey, data);
        },

        /**
         * @return {*}
         */
        initData = function () {
            return {
                'tmpManifestationId': null
            };
        },

        /**
         * @return {*}
         */
        getData = function () {
            var data = storage.get(cacheKey)();

            if ($.isEmptyObject(data)) {
                data = $.initNamespaceStorage('mage-cache-storage').localStorage.get(cacheKey);

                if ($.isEmptyObject(data)) {
                    data = initData();
                    saveData(data);
                }
            }

            return data;
        };

    return {
        /**
         * Setting the selected manifestation_id pulled from persistence storage
         *
         * @param {Object} data
         */
        setTmpManifestationId: function (data) {
            var obj = getData();

            obj.tmpManifestationId = data;
            saveData(obj);
        },

        /**
         * Pulling the selected manifestation_id from persistence storage
         *
         * @return {*}
         */
        getTmpManifestationId: function () {
            return getData().tmpManifestationId;
        }
    };
});
