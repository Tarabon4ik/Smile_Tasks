<?php
/**
 * RepositoryInterface Manifestation Place Link
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
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkSearchResultsInterface;

/**
 * Interface Manifestation Place Link Repository
 *
 * @api
 */
interface ManifestationPlaceLinkRepositoryInterface
{
    /**
     * Get link by ID
     *
     * @param int $linkId
     *
     * @return ManifestationPlaceLinkInterface
     *
     * @throws NoSuchEntityException If place with the specified ID does not exist
     * @throws LocalizedException
     */
    public function getById($linkId): ManifestationPlaceLinkInterface;

    /**
     * Get link by Place ID
     *
     * @param int $placeId
     *
     * @return ManifestationPlaceLinkInterface
     *
     * @throws NoSuchEntityException If place with the specified placeId does not exist
     * @throws LocalizedException
     */
    public function getByPlaceId($placeId): ManifestationPlaceLinkInterface;

    /**
     * Get link by Place ID
     *
     * @param int $manifestationId
     *
     * @return ManifestationPlaceLinkInterface
     *
     * @throws NoSuchEntityException If place with the specified manifestationId does not exist
     * @throws LocalizedException
     */
    public function getByManifestationId($manifestationId): ManifestationPlaceLinkInterface;

    /**
     * Get List
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return ManifestationPlaceLinkSearchResultsInterface
     *
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria = null): ManifestationPlaceLinkSearchResultsInterface;

    /**
     * Delete link
     *
     * @param ManifestationPlaceLinkInterface $link
     *
     * @return bool true on success
     *
     * @throws LocalizedException
     */
    public function delete(ManifestationPlaceLinkInterface $link);

    /**
     * Delete link by ID
     *
     * @param int $linkId
     *
     * @return bool true on success
     *
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($linkId);

    /**
     * Create or update a link
     *
     * @param ManifestationPlaceLinkInterface $link
     *
     * @return void
     *
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function save(ManifestationPlaceLinkInterface $link);
}
