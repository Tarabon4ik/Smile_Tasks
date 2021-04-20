<?php

namespace Smile\Onepage\Ui;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Smile\Onepage\Model\ConfigProviderInterface;

/**
 * Config Provider
 *
 */
class ConfigProvider implements ConfigProviderInterface
{
    /**
     * Configuration key-value
     */
    const CONFIG_KEY = 'manifestation_onepage';

    /**
     * XML Configuration tree with parameter value
     */
    const XML_CONFIG_TREE = 'manifestation/onepage/';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * ConfigProvider constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        UrlInterface $urlBuilder
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Return configuration array
     *
     * @return array|mixed
     */
    public function getConfig()
    {
        $onepageUrl = $this->urlBuilder->getUrl('onepage');

        return [
            self::CONFIG_KEY => [
                'backendModule' => $this->scopeConfig->getValue(self::XML_CONFIG_TREE . 'backend_module'),
                'manifestationListingController' => $onepageUrl . 'index/manifestationListing',
                'placeDataController' => $onepageUrl . 'index/placeData',
                'manifestationDataController' => $onepageUrl . 'index/manifestationData'
            ]
        ];
    }
}
