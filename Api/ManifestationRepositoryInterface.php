<?php
/**
 * RepositoryInterface Manifestation
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
use Smile\Manifestation\Api\Data\ManifestationInterface;
use Smile\Manifestation\Api\Data\ManifestationSearchResultsInterface;

/**
 * Interface ManifestationRepositoryInterface
 *
 * @api
 */
interface ManifestationRepositoryInterface
{
    /**
     * Get manifestation by ID
     *
     * @param int $manifestationId
     *
     * @return ManifestationInterface
     *
     * @throws NoSuchEntityException If manifestation with the specified ID does not exist
     * @throws LocalizedException
     */
    public function getById($manifestationId);

    /**
     * Get List
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return ManifestationSearchResultsInterface
     *
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): ManifestationSearchResultsInterface;

    /**
     * Delete manifestation
     *
     * @param ManifestationInterface $manifestation
     *
     * @return bool true on success
     *
     * @throws LocalizedException
     */
    public function delete(ManifestationInterface $manifestation);

    /**
     * Delete manifestation by ID
     *
     * @param int $manifestationId
     *
     * @return bool true on success
     *
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($manifestationId);

    /**
     * Create or update a manifestation
     *
     * @param ManifestationInterface $manifestation
     *
     * @return ManifestationInterface
     *
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function save(ManifestationInterface $manifestation);
}
