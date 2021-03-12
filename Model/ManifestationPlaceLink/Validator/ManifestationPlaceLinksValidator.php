<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Smile\Manifestation\Model\ManifestationPlaceLink\Validator;

use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface;
use Smile\Manifestation\Model\ManifestationPlaceLinksValidatorInterface;

/**
 * Responsible for Stock Source links validation
 */
class ManifestationPlaceLinksValidator
{
    /**
     * @var ManifestationPlaceLinksValidatorInterface
     */
    private $manifestationPlaceLinkValidator;

    /**
     * @var ValidationResultFactory
     */
    private $validationResultFactory;

    /**
     * @param ValidationResultFactory $validationResultFactory
     * @param ManifestationPlaceLinksValidatorInterface $manifestationPlaceLinkValidator
     */
    public function __construct(
        ValidationResultFactory $validationResultFactory,
        ManifestationPlaceLinksValidatorInterface $manifestationPlaceLinkValidator
    ) {
        $this->validationResultFactory = $validationResultFactory;
        $this->manifestationPlaceLinkValidator = $manifestationPlaceLinkValidator;
    }

    /**
     * @param ManifestationPlaceLinkInterface[] $links
     * @return ValidationResult
     */
    public function validate(array $links): ValidationResult
    {
        $errors = [[]];
        foreach ($links as $placeItem) {
            $validationResult = $this->manifestationPlaceLinkValidator->validate($placeItem);
            if (!$validationResult->isValid()) {
                $errors[] = $validationResult->getErrors();
            }
        }
        $errors = array_merge(...$errors);

        $validationResult = $this->validationResultFactory->create(['errors' => $errors]);
        return $validationResult;
    }
}
