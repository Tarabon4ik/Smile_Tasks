<?php

declare(strict_types=1);

namespace Smile\Onepage\Controller\Index;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Smile\Onepage\Model\Type\Onepage as OnepageTypeModel;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var OnepageTypeModel
     */
    protected $onepageTypeModel;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * Onepage Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param OnepageTypeModel $onepageTypeModel
     * @param CustomerSession $customerSession
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        OnepageTypeModel $onepageTypeModel,
        CustomerSession $customerSession
    ) {
        parent::__construct(
            $context
        );
        $this->resultPageFactory = $resultPageFactory;
        $this->onepageTypeModel = $onepageTypeModel;
        $this->customerSession = $customerSession;
    }

    /**
     * Manofestation onepage
     *
     * @return ResultInterface
     */
    public function execute()
    {
        // generate session ID only if connection is unsecure according to issues in session_regenerate_id function.
        // @see http://php.net/manual/en/function.session-regenerate-id.php
        if (!$this->isSecureRequest()) {
            $this->customerSession->regenerateId();
        }

        $this->onepageTypeModel->initCheckout();

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Manifestation'));

        return $resultPage;
    }

    /**
     * Checks if current request uses SSL and referer also is secure.
     *
     * @return bool
     */
    private function isSecureRequest(): bool
    {
        $request = $this->getRequest();

        $referrer = $request->getHeader('referer');
        $secure = false;

        if ($referrer) {
            $scheme = parse_url($referrer, PHP_URL_SCHEME);
            $secure = $scheme === 'https';
        }

        return $secure && $request->isSecure();
    }
}
