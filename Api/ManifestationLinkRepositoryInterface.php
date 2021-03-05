<?php
/**
 * RepositoryInterface ManifestationLink
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Smile\Manifestation\Api\Data\ManifestationLinkInterface;

/**
 * Interface ManifestationRepositoryInterface
 *
 * @api
 */
interface ManifestationLinkRepositoryInterface
{
    /**
     * Get manifestation link by ID
     *
     * @param int $manifestationLinkId
     *
     * @return ManifestationLinkInterface
     *
     * @throws NoSuchEntityException If manifestation link with the specified ID does not exist
     * @throws LocalizedException
     */
    public function getById(int $manifestationLinkId);

    /**
     * Get List
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return ManifestationLinkInterface
     *
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete manifestation link
     *
     * @param ManifestationLinkInterface $manifestationLink
     *
     * @return bool true on success
     *
     * @throws LocalizedException
     */
    public function delete(ManifestationLinkInterface $manifestationLink);

    /**
     * Delete manifestation link by ID
     *
     * @param int $manifestationLinkId
     *
     * @return bool true on success
     *
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($manifestationLinkId);

    /**
     * Create or update a manifestation link
     *
     * @param ManifestationLinkInterface $manifestationLink
     *
     * @return ManifestationLinkInterface
     *
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function save(ManifestationLinkInterface $manifestationLink);
}
