<?php
/**
 * Manifestation Table Install Data
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Smile\Manifestation\Setup\ManifestationSetupFactory;

/**
 * InstallData
 */
class InstallData implements InstallDataInterface
{
    /**
     * Manifestation Setup Factory
     *
     * @var ManifestationSetupFactory
     */
    protected $manifestationSetupFactory;

    /**
     * InstallData Constructor
     *
     * @param ManifestationSetupFactory $manifestationSetupFactory
     */
    public function __construct(
        ManifestationSetupFactory $manifestationSetupFactory
    ) {
        $this->manifestationSetupFactory = $manifestationSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->manifestationSetupFactory->create(['setup' => $setup])
            ->installEntities();

        $setup->endSetup();
    }
}
