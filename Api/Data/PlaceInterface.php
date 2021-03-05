<?php
/**
 * Interface Place
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Api\Data;

/**
 * Place Interface
 *
 * @api
 */
interface PlaceInterface
{
    /**#@+
     * Constants defined for data array
     */
    const ID = 'entity_id';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const LOCATION = 'location';
    const LONGITUDE = 'longitude';
    const LATITUDE = 'latitude';
    const IMAGE = 'image';
    /**#@-*/

    /**
     * Place id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set Place id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Place name
     *
     * @return string
     */
    public function getName();

    /**
     * Set place name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Place description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Set place description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Place location
     *
     * @return string
     */
    public function getLocation();

    /**
     * Set place location
     *
     * @param string $location
     * @return $this
     */
    public function setLocation($location);

    /**
     * Place longitude
     *
     * @return string|null
     */
    public function getLongitude();

    /**
     * Set place longitude
     *
     * @param string $longitude
     * @return $this
     */
    public function setLongitude($longitude);

    /**
     * Place latitude
     *
     * @return string|null
     */
    public function getLatitude();

    /**
     * Set place latitude
     *
     * @param string $latitude
     * @return $this
     */
    public function setLatitude($latitude);

    /**
     * Get media gallery entries
     *
     * @return string|null
     */
    public function getImage();

    /**
     * Set media gallery entries
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image);
}
