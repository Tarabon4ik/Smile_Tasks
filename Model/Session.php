<?php

namespace Smile\Onepage\Model;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\State;
use Magento\Framework\Session\Config\ConfigInterface;
use Magento\Framework\Session\SaveHandlerInterface;
use Magento\Framework\Session\SessionManager;
use Magento\Framework\Session\SidResolverInterface;
use Magento\Framework\Session\StorageInterface;
use Magento\Framework\Session\ValidatorInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

/**
 * Represents the session data for the manifestation_onepage process
 *
 * @api
 */
class Session extends SessionManager
{
    const CHECKOUT_STATE_BEGIN = 'begin';

    /**
     * Customer Data Object
     *
     * @var CustomerInterface|null
     */
    protected $_customer;

    /**
     * @param Http $request
     * @param SidResolverInterface $sidResolver
     * @param ConfigInterface $sessionConfig
     * @param SaveHandlerInterface $saveHandler
     * @param ValidatorInterface $validator
     * @param StorageInterface $storage
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param State $appState
     */
    public function __construct(
        Http $request,
        SidResolverInterface $sidResolver,
        ConfigInterface $sessionConfig,
        SaveHandlerInterface $saveHandler,
        ValidatorInterface $validator,
        StorageInterface $storage,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        State $appState
    ) {
        parent::__construct(
            $request,
            $sidResolver,
            $sessionConfig,
            $saveHandler,
            $validator,
            $storage,
            $cookieManager,
            $cookieMetadataFactory,
            $appState
        );
    }

    /**
     * Set customer data.
     *
     * @param CustomerInterface|null $customer
     * @return Session
     * @codeCoverageIgnore
     */
    public function setCustomerData($customer)
    {
        $this->_customer = $customer;
        return $this;
    }

    /**
     * Associate data to a specified step of the manifestation_onepage process
     *
     * @param string $step
     * @param array|string $data
     * @param bool|string|null $value
     * @return $this
     */
    public function setStepData($step, $data, $value = null)
    {
        $steps = $this->getSteps();
        if ($value === null) {
            if (is_array($data)) {
                $steps[$step] = $data;
            }
        } else {
            if (!isset($steps[$step])) {
                $steps[$step] = [];
            }
            if (is_string($data)) {
                $steps[$step][$data] = $value;
            }
        }
        $this->setSteps($steps);

        return $this;
    }

    /**
     * Return the data associated to a specified step
     *
     * @param string|null $step
     * @param string|null $data
     * @return array|string|bool
     */
    public function getStepData($step = null, $data = null)
    {
        $steps = $this->getSteps();
        if ($step === null) {
            return $steps;
        }
        if (!isset($steps[$step])) {
            return false;
        }
        if ($data === null) {
            return $steps[$step];
        }
        if (!is_string($data) || !isset($steps[$step][$data])) {
            return false;
        }
        return $steps[$step][$data];
    }

    /**
     * Unset all session data and quote
     *
     * @return $this
     */
    public function clearStorage()
    {
        parent::clearStorage();
        return $this;
    }

    /**
     * Revert the state of the checkout to the beginning
     *
     * @return $this
     * @codeCoverageIgnore
     */
    public function resetCheckout()
    {
        $this->setCheckoutState(self::CHECKOUT_STATE_BEGIN);
        return $this;
    }
}
