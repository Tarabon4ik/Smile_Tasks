<?php
declare(strict_types=1);

namespace Smile\Manifestation\Model\Place;

/**
 * Prepare region data. Specified for form structure
 */
class PlaceRegionDataProcessor
{
    /**
     * Processes place region data
     *
     * @param array $data
     * @return array
     */
    public function execute(array $data): array
    {
        if ($this->isFieldEmpty('region_id', $data)) {
            $data['region_id'] = null;
        }

        if ($this->isFieldEmpty('region', $data)) {
            $data['region'] = null;
        }

        return $data;
    }

    /**
     * Checks whether field has post value and this value doesn't empty
     *
     * @param string $fieldName
     * @param array $data
     *
     * @return bool
     */
    private function isFieldEmpty(string $fieldName, array $data): bool
    {
        return !isset($data[$fieldName]) || '' === $data[$fieldName];
    }
}
