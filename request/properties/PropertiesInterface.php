<?php

/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 18.11.16
 * Time: 22:27
 */

namespace bmwx591\privat24\request\properties;

interface PropertiesInterface
{
    /**
     * Validate properties values
     * @return boolean
     */
    public function validate();

    /**
     * @return array 
     */
    public function getAttributes();
}