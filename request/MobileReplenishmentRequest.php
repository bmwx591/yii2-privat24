<?php

namespace bmwx591\privat24\request;

use bmwx591\privat24\request\properties\MobileReplenishmentProperties;

class MobileReplenishmentRequest extends PayRequest
{

    /**
     * @param array $properties Request properties
     * @return MobileReplenishmentProperties
     */
    protected function getPropertiesInstance(array $properties)
    {
        return new MobileReplenishmentProperties($properties);
    }
}
