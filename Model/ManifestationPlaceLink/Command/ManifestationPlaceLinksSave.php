<?php

declare(strict_types=1);

namespace Smile\Manifestation\Model\ManifestationPlaceLink\Command;

use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Validation\ValidationException;
use Smile\Manifestation\Model\ResourceModel\ManifestationPlaceLink\SaveMultiple;
use Smile\Manifestation\Model\ManifestationPlaceLink\Validator\ManifestationPlaceLinksValidator;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface;
use Smile\Manifestation\Api\ManifestationPlaceLinksSaveInterface;
use Psr\Log\LoggerInterface;

/**
 * @inheritdoc
 */
class ManifestationPlaceLinksSave implements ManifestationPlaceLinksSaveInterface
{
    /**
     * @var SaveMultiple
     */
    private $saveMultiple;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ManifestationPlaceLinksValidator
     */
    private $manifestationPlaceLinksValidator;

    /**
     * @param ManifestationPlaceLinksValidator $manifestationPlaceLinksValidator
     * @param SaveMultiple $saveMultiple
     * @param LoggerInterface $logger
     */
    public function __construct(
        ManifestationPlaceLinksValidator $manifestationPlaceLinksValidator,
        SaveMultiple $saveMultiple,
        LoggerInterface $logger
    ) {
        $this->saveMultiple = $saveMultiple;
        $this->logger = $logger;
        $this->manifestationPlaceLinksValidator = $manifestationPlaceLinksValidator;
    }

    /**
     * @param ManifestationPlaceLinkInterface[] $links
     * @throws CouldNotSaveException
     * @throws InputException
     * @throws ValidationException
     */
    public function execute(array $links): void
    {
        if (empty($links)) {
            throw new InputException(__('Input data is empty'));
        }

        $validationResult = $this->manifestationPlaceLinksValidator->validate($links);
        if (!$validationResult->isValid()) {
            throw new ValidationException(__('Validation Failed'), null, 0, $validationResult);
        }

        try {
            $this->saveMultiple->execute($links);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw new CouldNotSaveException(__('Could not save ManifestationPlaceLinks'), $e);
        }
    }
}
