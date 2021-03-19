<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Smile\Manifestation\Model;

use Magento\Framework\Validation\ValidationResult;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface;

/**
 * Responsible for Stock Source link validation
 * Extension point for base validation
 *
 * @api
 */
interface ManifestationPlaceLinksValidatorInterface
{
    /**
     * @param ManifestationPlaceLinkInterface $link
     * @return ValidationResult
     */
    public function validate(ManifestationPlaceLinkInterface $link): ValidationResult;
}
