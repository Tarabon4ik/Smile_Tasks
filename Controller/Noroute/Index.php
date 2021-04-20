<?php

namespace Smile\Onepage\Controller\Noroute;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Exception\NotFoundException;

class Index extends Action
{
    /**
     * Manifestation_Onepage page not found controller
     *
     * @throws NotFoundException
     * @return void
     * @codeCoverageIgnore
     */
    public function execute()
    {
        throw new NotFoundException(__('Page not found.'));
    }
}
