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
                        'type' => 'static',
                        'label' => 'Title',
                        'input' => 'text',
                        'frontend_class' => 'validate-length maximum-length-64',
                        'unique' => true,
                        'sort_order' => 2,
                        'searchable' => true,
                        'comparable' => true,
                        'visible_in_advanced_search' => true,
                    ],
                    'created_at' => [
                        'type' => 'static',
                        'input' => 'date',
                        'sort_order' => 3,
                        'visible' => true,
                    ],
                    'updated_at' => [
                        'type' => 'static',
                        'input' => 'date',
                        'sort_order' => 4,
                        'visible' => true,
                    ],
                    'description' => [
                        'type' => 'text',
                        'label' => 'Description',
                        'input' => 'textarea',
                        'required' => false,
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
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
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'group' => 'General Information',
                    ],
                    'meta_title' => [
                        'type' => 'varchar',
                        'label' => 'Page Title',
                        'input' => 'text',
                        'required' => false,
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'group' => 'General Information',
                    ],
                    'meta_description' => [
                        'type' => 'text',
                        'label' => 'Meta Description',
                        'input' => 'textarea',
                        'required' => false,
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'group' => 'General Information',
                    ],
                    'start_date' => [
                        'type' => 'datetime',
                        'input' => 'date',
                        'visible' => true,
                    ],
                    'end_date' => [
                        'type' => 'datetime',
                        'input' => 'date',
                        'visible' => true,
                    ],
                    'is_need_electricity' => [
                        'type' => 'int',
                        'label' => 'Is Anchor',
                        'input' => 'select',
                        'source' => Boolean::class,
                        'required' => false,
                        'group' => 'General Information',
                    ],
                    'is_need_water' => [
                        'type' => 'int',
                        'label' => 'Is Anchor',
                        'input' => 'select',
                        'source' => Boolean::class,
                        'required' => false,
                        'group' => 'General Information',
                    ],
                ],
            ],

            $placeEntity => [
                'entity_model' => Place::class,
                'table' => $placeEntity . '_entity',
                'attributes' => [
                    'name' => [
                        'type' => 'static',
                        'label' => 'Name',
                        'input' => 'text',
                        'frontend_class' => 'validate-length maximum-length-64',
                        'unique' => true,
                        'sort_order' => 2,
                        'searchable' => true,
                        'comparable' => true,
                        'visible_in_advanced_search' => true,
                    ],
                    'created_at' => [
                        'type' => 'static',
                        'input' => 'date',
                        'sort_order' => 3,
                        'visible' => true,
                    ],
                    'updated_at' => [
                        'type' => 'static',
                        'input' => 'date',
                        'sort_order' => 4,
                        'visible' => true,
                    ],
                    'description' => [
                        'type' => 'text',
                        'label' => 'Description',
                        'input' => 'textarea',
                        'required' => false,
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'wysiwyg_enabled' => true,
                        'is_html_allowed_on_front' => true,
                        'group' => 'General Information',
                    ],
                    'enabled' => [
                        'type' => 'int',
                        'label' => 'Status',
                        'input' => 'select',
                        'source' => Boolean::class,
                        'required' => false,
                        'group' => 'Display Settings',
                    ],
                    'email' => [
                        'type' => 'varchar',
                        'label' => 'Email',
                        'input' => 'text',
                        'required' => false,
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'group' => 'General Information',
                    ],
                    'contact_name' => [
                        'type' => 'varchar',
                        'label' => 'Contact Name',
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => 6,
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'group' => 'General Information',
                    ],
                    'country_id' => [
                        'type' => 'varchar',
                        'label' => 'Country',
                        'input' => 'select',
                        'source' => \Magento\Directory\Model\Config\Source\Country::class,
                        'required' => false,
                        'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                        'visible' => true,
                        'user_defined' => false,
                        'searchable' => false,
                        'filterable' => false,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'unique' => false,
                        'group' => 'Geospatial Info',
                        'is_used_in_grid' => true,
                        'is_visible_in_grid' => false,
                        'is_filterable_in_grid' => true,
                    ],
                    'region_id' => [
                        'type' => 'varchar',
                        'label' => 'Region ID',
                        'input' => 'select',
                        'source' => \Magento\Customer\Model\Data\Region::class,
                        'required' => false,
                        'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                        'visible' => true,
                        'user_defined' => false,
                        'searchable' => false,
                        'filterable' => false,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'unique' => false,
                        'group' => 'Geospatial Info',
                        'is_used_in_grid' => true,
                        'is_visible_in_grid' => false,
                        'is_filterable_in_grid' => true,
                    ],
                    'region' => [
                        'type' => 'varchar',
                        'label' => 'Region',
                        'input' => 'text',
                        'required' => false,
                        'user_defined' => true,
                        'searchable' => true,
                        'filterable' => true,
                        'comparable' => true,
                        'visible_in_advanced_search' => true,
                        'is_used_in_grid' => true,
                        'is_visible_in_grid' => false,
                        'is_filterable_in_grid' => true,
                    ],
                    'city' => [
                        'type' => 'varchar',
                        'label' => 'City',
                        'input' => 'text',
                        'required' => false,
                        'user_defined' => true,
                        'searchable' => true,
                        'filterable' => true,
                        'comparable' => true,
                        'visible_in_advanced_search' => true,
                        'is_used_in_grid' => true,
                        'is_visible_in_grid' => false,
                        'is_filterable_in_grid' => true,
                    ],
                    'street' => [
                        'type' => 'varchar',
                        'label' => 'Street',
                        'input' => 'text',
                        'required' => false,
                        'user_defined' => true,
                        'searchable' => true,
                        'filterable' => true,
                        'comparable' => true,
                        'visible_in_advanced_search' => true,
                        'is_used_in_grid' => true,
                        'is_visible_in_grid' => false,
                        'is_filterable_in_grid' => true,
                    ],
                    'phone' => [
                        'type' => 'varchar',
                        'label' => 'Phone',
                        'input' => 'text',
                        'required' => false,
                        'user_defined' => true,
                        'searchable' => true,
                        'filterable' => true,
                        'comparable' => true,
                        'visible_in_advanced_search' => true,
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
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
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
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
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
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'group' => 'General Information',
                    ],
                ],
            ],
        ];

        return $entities;
    }
}
