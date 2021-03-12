<?php
/**
 * Manifestation Table Setup
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Setup;

use Magento\Catalog\Model\Category\Attribute\Backend\Image;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Eav\Setup\EavSetup;
use Smile\Manifestation\Model\Manifestation;
use Smile\Manifestation\Model\Place;

/**
 * Class ManifestationSetup
 */
class ManifestationSetup extends EavSetup
{
    /**
     * Default entities and attributes
     *
     * @return array
     */
    public function getDefaultEntities()
    {
        $manifestationEntity = Manifestation::ENTITY;

        $placeEntity = Place::ENTITY;

        $entities = [
            $manifestationEntity => [
                'entity_model' => Manifestation::class,
                'table' => $manifestationEntity . '_entity',
                'attributes' => [
                    'title' => [
                        'type' => 'varchar',
                        'label' => 'Title',
                        'input' => 'text',
                        'sort_order' => 1,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                    ],
                    'description' => [
                        'type' => 'text',
                        'label' => 'Description',
                        'input' => 'textarea',
                        'required' => false,
                        'sort_order' => 2,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'wysiwyg_enabled' => true,
                        'is_html_allowed_on_front' => true,
                        'group' => 'General Information',
                    ],
                    'image' => [
                        'type' => 'varchar',
                        'label' => 'Image',
                        'input' => 'image',
                        'backend' => Image::class,
                        'required' => false,
                        'sort_order' => 3,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                    ],
                    'meta_title' => [
                        'type' => 'varchar',
                        'label' => 'Page Title',
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => 4,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                    ],
                    'meta_description' => [
                        'type' => 'text',
                        'label' => 'Meta Description',
                        'input' => 'textarea',
                        'required' => false,
                        'sort_order' => 5,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                    ],
                    'created_at' => [
                        'type' => 'static',
                        'input' => 'date',
                        'sort_order' => 6,
                        'visible' => false,
                    ],
                    'updated_at' => [
                        'type' => 'static',
                        'input' => 'date',
                        'sort_order' => 7,
                        'visible' => false,
                    ],
                    'start_date' => [
                        'type' => 'static',
                        'input' => 'date',
                        'sort_order' => 8,
                        'visible' => false,
                    ],
                    'end_date' => [
                        'type' => 'static',
                        'input' => 'date',
                        'sort_order' => 9,
                        'visible' => false,
                    ],
                    'is_need_electricity' => [
                        'type' => 'int',
                        'label' => 'Is Anchor',
                        'input' => 'select',
                        'source' => Boolean::class,
                        'required' => false,
                        'sort_order' => 10,
                        'group' => 'Display Settings',
                    ],
                    'is_need_water' => [
                        'type' => 'int',
                        'label' => 'Is Anchor',
                        'input' => 'select',
                        'source' => Boolean::class,
                        'required' => false,
                        'sort_order' => 11,
                        'group' => 'Display Settings',
                    ],
                ],
            ],

            $placeEntity => [
                'entity_model' => Place::class,
                'table' => $placeEntity . '_entity',
                'attributes' => [
                    'name' => [
                        'type' => 'varchar',
                        'label' => 'Name',
                        'input' => 'text',
                        'sort_order' => 1,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                    ],
                    'description' => [
                        'type' => 'text',
                        'label' => 'Description',
                        'input' => 'textarea',
                        'required' => false,
                        'sort_order' => 2,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'wysiwyg_enabled' => true,
                        'is_html_allowed_on_front' => true,
                        'group' => 'General Information',
                    ],
                    'location' => [
                        'type' => 'varchar',
                        'label' => 'Location',
                        'input' => 'textarea',
                        'required' => false,
                        'note' => 'Maximum 255 chars',
                        'class' => 'validate-length maximum-length-255',
                        'sort_order' => 3,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'Geo Data',
                        'is_used_in_grid' => true,
                        'is_visible_in_grid' => false,
                        'is_filterable_in_grid' => true,
                    ],
                    'longitude' => [
                        'type' => 'varchar',
                        'label' => 'Longitude',
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => 4,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'Geo Data',
                        'is_used_in_grid' => true,
                        'is_visible_in_grid' => false,
                        'is_filterable_in_grid' => true,
                    ],
                    'latitude' => [
                        'type' => 'varchar',
                        'label' => 'Latitude',
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => 5,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'Geo Data',
                        'is_used_in_grid' => true,
                        'is_visible_in_grid' => false,
                        'is_filterable_in_grid' => true,
                    ],
                    'image' => [
                        'type' => 'varchar',
                        'label' => 'Image',
                        'input' => 'image',
                        'backend' => Image::class,
                        'required' => false,
                        'sort_order' => 6,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                    ],
                    'created_at' => [
                        'type' => 'static',
                        'input' => 'date',
                        'sort_order' => 7,
                        'visible' => false,
                    ],
                    'updated_at' => [
                        'type' => 'static',
                        'input' => 'date',
                        'sort_order' => 8,
                        'visible' => false,
                    ],
                ],
            ],
        ];

        return $entities;
    }
}
