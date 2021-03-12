<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Smile\Manifestation\Api\Data;

/**
 * Represents relation between Stock and Source entities.
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface ManifestationPlaceLinkInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY = 'manifestation_place_link';

    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const MANIFESTATION_ID = 'manifestation_id';
    const PLACE_ID = 'place_id';
    const PRIORITY = 'priority';
    /**#@-*/

    /**
     * Get manifestation id
     *
     * @return int|null
     */
    public function getManifestationId(): ?int;

    /**
     * Set manifestation id
     *
     * @param int|null $manifestationId
     * @return void
     */
    public function setManifestationId(?int $manifestationId): void;

    /**
     * Get Place Id of the manifestation
     *
     * @return int|null
     */
    public function getPlaceId(): ?string;

    /**
     * Set Place Id of the manifestation
     *
     * @param int|null $placeId
     *
     * @return void
     */
    public function setPlaceId(?int $placeId): void;

    /**
     * Get priority of the link
     *
     * @return int|null
     */
    public function getPriority(): ?int;

    /**
     * Set priority of the link
     *
     * @param int $priority
     *
     * @return void
     */
    public function setPriority(?int $priority): void;
}
