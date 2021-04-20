<?php

namespace Smile\Onepage\Model;

use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Magento\Customer\Model\Address\CustomerAddressDataProvider;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Model\Url as CustomerUrlManager;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Default Config Provider
 *
 */
class DefaultConfigProvider implements ConfigProviderInterface
{
    /**#@+
     * Constants defined for xml config paths
     */
    const XML_CONFIG_PATH_CUSTOMER_MUST_BE_LOGGED =  'manifestation/onepage/customer_must_be_logged';
    const XML_CONFIG_PATH_IS_GUEST_ONEPAGE_ENABLED =  'manifestation/onepage/guest_onepage';
    const XML_CONFIG_PATH_ENABLE_AUTOCOMPLETE =  'manifestation/onepage/autocomplete_on_storefront';
    /**#@-*/

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var HttpContext
     */
    protected $httpContext;

    /**
     * @var CustomerAddressDataProvider
     */
    protected $customerAddressData;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var CustomerUrlManager
     */
    protected $customerUrlManager;

    /**
     * DefaultConfigProvider constructor
     *
     * @param UrlInterface $urlBuilder
     * @param CustomerRepository $customerRepository
     * @param CustomerSession $customerSession
     * @param HttpContext $httpContext
     * @param ScopeConfigInterface $scopeConfig
     * @param CustomerUrlManager $customerUrlManager
     */
    public function __construct(
        UrlInterface $urlBuilder,
        CustomerRepository $customerRepository,
        CustomerSession $customerSession,
        HttpContext $httpContext,
        ScopeConfigInterface $scopeConfig,
        CustomerUrlManager $customerUrlManager
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
        $this->httpContext = $httpContext;
        $this->scopeConfig = $scopeConfig;
        $this->customerUrlManager = $customerUrlManager;
    }

    /**
     * Return configuration array
     *
     * @return array|mixed
     */
    public function getConfig()
    {
        $output['isGuestOnepageAllowed'] = $this->isGuestOnepageAllowed();
        $output['isCustomerLoggedIn'] = $this->isCustomerLoggedIn();
        $output['customerData'] = $this->getCustomerData();
        $output['registerUrl'] = $this->getRegisterUrl();
        $output['forgotPasswordUrl'] = $this->getForgotPasswordUrl();
        $output['autocomplete'] = $this->isAutocompleteEnabled();
        $output['isCustomerLoginRequired'] = $this->isCustomerLoginRequired();
        $output['manifestationUrl'] = $this->getManifestationOnepageUrl();
        $output['pageNotFoundUrl'] = $this->pageNotFoundUrl();

        return $output;
    }

    /**
     * Retrieve manifestation_onepage URL
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getManifestationOnepageUrl()
    {
        return $this->urlBuilder->getUrl('onepage');
    }

    /**
     * Retrieve manifestation_onepage URL
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function pageNotFoundUrl()
    {
        return $this->urlBuilder->getUrl('onepage/noroute');
    }

    /**
     * Retrieve customer data
     *
     * @return array
     */
    private function getCustomerData(): array
    {
        $customerData = [];
        if ($this->isCustomerLoggedIn()) {
            /** @var \Magento\Customer\Api\Data\CustomerInterface $customer */
            $customer = $this->customerRepository->getById($this->customerSession->getCustomerId());
            $customerData = $customer->__toArray();
            $customerData['addresses'] = $this->customerAddressData->getAddressDataByCustomer($customer);
        }
        return $customerData;
    }
    /**
     * Check if customer is logged in
     *
     * @return bool
     * @codeCoverageIgnore
     */
    private function isCustomerLoggedIn()
    {
        return (bool)$this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
    }

    /**
     * Check if customer must be logged in to proceed with onepage
     *
     * @return bool
     * @codeCoverageIgnore
     */
    private function isCustomerLoginRequired()
    {
        return $this->scopeConfig->isSetFlag(self::XML_CONFIG_PATH_CUSTOMER_MUST_BE_LOGGED);
    }

    /**
     * Check if guest onepage is allowed
     *
     * @return bool
     * @codeCoverageIgnore
     */
    private function isGuestOnepageAllowed()
    {
        return $this->scopeConfig->isSetFlag(self::XML_CONFIG_PATH_IS_GUEST_ONEPAGE_ENABLED);
    }

    /**
     * Retrieve customer registration URL
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getRegisterUrl()
    {
        return $this->customerUrlManager->getRegisterUrl();
    }

    /**
     * Return forgot password URL
     *
     * @return string
     * @codeCoverageIgnore
     */
    private function getForgotPasswordUrl()
    {
        return $this->customerUrlManager->getForgotPasswordUrl();
    }

    /**
     * Is autocomplete enabled for storefront
     *
     * @return string
     * @codeCoverageIgnore
     */
    private function isAutocompleteEnabled()
    {
        return $this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_ENABLE_AUTOCOMPLETE,
            ScopeInterface::SCOPE_STORE
        ) ? 'on' : 'off';
    }
}
