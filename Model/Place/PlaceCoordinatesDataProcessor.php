<?php

declare(strict_types=1);

namespace Smile\Manifestation\Model\Place;

/**
 * Prepare place coordinates data (latitude and longitude). Specified for form structure
 */
class PlaceCoordinatesDataProcessor
{
    /**
     * @param array $data
     * @return array
     */
    public function execute(array $data): array
    {
        if (!isset($data['latitude']) || '' === $data['latitude']) {
            $data['latitude'] = null;
        }

        if (!isset($data['longitude']) || '' === $data['longitude']) {
            $data['longitude'] = null;
        }

        return $data;
    }
}
