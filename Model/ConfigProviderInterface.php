<?php

namespace Smile\Onepage\Model;

/**
 * Interface ConfigProviderInterface
 * @api
 */
interface ConfigProviderInterface
{
    /**
     * Retrieve assoc array of manifestation-onepage configuration
     *
     * @return array
     */
    public function getConfig();
}
