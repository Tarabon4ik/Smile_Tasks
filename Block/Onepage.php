<?php

namespace Smile\Onepage\Block;

use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Smile\Onepage\Block\Manifestation\LayoutProcessorInterface;
use Smile\Onepage\Model\CompositeConfigProvider;

/**
 * Onepage manifestation block
 * @api
 */
class Onepage extends Template
{
    /**
     * @var FormKey
     */
    protected $formKey;

    /**
     * @var bool
     */
    protected $_isScopePrivate = false;

    /**
     * @var array
     */
    protected $jsLayout;

    /**
     * @var array|LayoutProcessorInterface[]
     */
    protected $layoutProcessors;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var CompositeConfigProvider
     */
    protected $configProvider;

    /**
     * @param Context $context
     * @param FormKey $formKey
     * @param CompositeConfigProvider $configProvider
     * @param array $layoutProcessors
     * @param array $data
     * @param Json|null $serializer
     * @param SerializerInterface|null $serializerInterface
     */
    public function __construct(
        Context $context,
        FormKey $formKey,
        CompositeConfigProvider $configProvider,
        array $layoutProcessors = [],
        array $data = [],
        Json $serializer = null,
        SerializerInterface $serializerInterface = null
    ) {
        parent::__construct($context, $data);
        $this->formKey = $formKey;
        $this->configProvider = $configProvider;
        $this->layoutProcessors = $layoutProcessors;
        $this->_isScopePrivate = true;
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->serializer = $serializerInterface ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\Serialize\Serializer\JsonHexTag::class);
    }

    /**
     * @inheritdoc
     */
    public function getJsLayout()
    {
        foreach ($this->layoutProcessors as $processor) {
            $this->jsLayout = $processor->process($this->jsLayout);
        }

        return $this->serializer->serialize($this->jsLayout);
    }

    /**
     * Retrieve form key
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    /**
     * Get base url for block.
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * Retrieve manifestation-onepage configuration
     *
     * @return array
     * @codeCoverageIgnore
     */
    public function getManifestationOnepageConfig()
    {
        return $this->configProvider->getConfig();
    }

    /**
     * Retrieve serialized manifestation-onepage config
     *
     * @return bool|string
     */
    public function getSerializedManifestationOnepageConfig()
    {
        return  $this->serializer->serialize($this->getManifestationOnepageConfig());
    }
}
