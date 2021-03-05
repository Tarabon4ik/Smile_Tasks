<?php
/**
 * RepositoryInterface Place
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
use Smile\Manifestation\Api\Data\PlaceInterface;

/**
 * Interface Place Repository
 *
 * @api
 */
interface PlaceRepositoryInterface
{
    /**
     * Get place by ID
     *
     * @param int $placeId
     *
     * @return PlaceInterface
     *
     * @throws NoSuchEntityException If place with the specified ID does not exist
     * @throws LocalizedException
     */
    public function getById(int $placeId);

    /**
     * Get List
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return PlaceInterface
     *
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete place
     *
     * @param PlaceInterface $place
     *
     * @return bool true on success
     *
     * @throws LocalizedException
     */
    public function delete(PlaceInterface $place);

    /**
     * Delete place by ID
     *
     * @param int $placeId
     *
     * @return bool true on success
     *
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($placeId);

    /**
     * Create or update a place
     *
     * @param PlaceInterface $place
     *
     * @return PlaceInterface
     *
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function save(PlaceInterface $place);
}
