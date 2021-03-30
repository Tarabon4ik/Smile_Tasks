/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'Magento_Ui/js/grid/columns/column'
], function (Column) {
    'use strict';

    return Column.extend({
        defaults: {
            bodyTmpl: 'Smile_Manifestation/manifestation/grid/cell/assigned-places-cell.html',
            itemsToDisplay: 5
        },

        /**
         *
         * @param {Array} record
         * @returns {Array}
         */
        getTooltipData: function (record) {
            return record[this.index].map(function (place) {
                return {
                    entity_id: place.entity_id,
                    name: place.name
                };
            });
        },

        /**
         * @param {Object} record - Record object
         * @returns {Array} Result array
         */
        getPlacesAssignedToManifestationOrderedByPriority: function (record) {
            return this.getTooltipData(record).slice(0, this.itemsToDisplay);
        }
    });
});
