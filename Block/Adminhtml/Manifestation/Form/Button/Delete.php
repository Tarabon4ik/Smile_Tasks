<?php

namespace Smile\Manifestation\Block\Adminhtml\Manifestation\Form\Button;

use Magento\Catalog\Block\Adminhtml\Category\AbstractCategory;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Delete
 */
class Delete extends AbstractCategory implements ButtonProviderInterface
{
    /**
     * Get Button Data
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'id' => 'delete',
            'label' => __('Delete'),
            'on_click' => "deleteConfirm('" . __('Are you sure you want to delete this manifestation?') . "', '"
                . $this->getDeleteUrl() . "', {data: {}})",
            'class' => 'delete',
            'sort_order' => 10
        ];
    }

    /**
     * Get Delete Url
     *
     * @param array $args
     *
     * @return string
     */
    public function getDeleteUrl(array $args = [])
    {
        $params = array_merge($this->getDefaultUrlParams(), $args);

        return $this->getUrl('manifestation/manifestation/delete', $params);
    }

    /**
     * Get Default Url Params
     *
     * @return array
     */
    protected function getDefaultUrlParams()
    {
        return ['_current' => true, '_query' => ['isAjax' => null]];
    }
}
