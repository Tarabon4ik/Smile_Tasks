<?php
namespace Smile\Onepage\Block\Manifestation;

/**
 * Layout processor interface.
 *
 * Can be used to provide a custom logic for manifestation JS layout preparation.
 *
 * @see \Smile\Manifestation\Block\Onepage
 *
 * @api
 */
interface LayoutProcessorInterface
{
    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout);
}
