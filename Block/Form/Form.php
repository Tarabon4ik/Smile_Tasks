<?php

namespace Smile\Checkout\Block\Form;

use Magento\Framework\View\Element\Template;
use Magento\Backend\Block\Template\Context;

class Form extends Template
{
    public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    public function getFormAction()
    {
        return $this->getUrl('checkout/index/submit', ['_secure' => true]);
    }
}
