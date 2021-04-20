<?php

namespace Smile\Onepage\Model\Type;

use Magento\Customer\Model\Session as CustomerSession;
use Smile\Onepage\Model\Session as ManifestationOnepageSession;

/**
 * Class Onepage
 */
class Onepage
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var ManifestationOnepageSession
     */
    protected $manifestationSession;

    /**
     * Onepage constructor
     *
     * @param ManifestationOnepageSession $manifestationSession
     * @param CustomerSession $customerSession
     */
    public function __construct(
        ManifestationOnepageSession $manifestationSession,
        CustomerSession $customerSession
    ) {
        $this->manifestationSession = $manifestationSession;
        $this->customerSession = $customerSession;
    }

    /**
     * Get frontend checkout session object
     *
     * @return ManifestationOnepageSession
     * @codeCoverageIgnore
     */
    public function getCheckout()
    {
        return $this->manifestationSession;
    }

    /**
     * Get customer session object
     *
     * @return CustomerSession
     */
    public function getCustomerSession()
    {
        return $this->customerSession;
    }

    /**
     * Initialize quote state to be valid for one page checkout
     *
     * @return $this
     */
    public function initCheckout()
    {
        $manifestation = $this->getCheckout();
        $customerSession = $this->getCustomerSession();
        if (is_array($manifestation->getStepData())) {
            foreach ($manifestation->getStepData() as $step => $data) {
                if (!($step === 'login' || $customerSession->isLoggedIn())) {
                    $manifestation->setStepData($step, 'allow', false);
                }
            }
        }

        return $this;
    }
}
