<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 16.03.17
 * Time: 2:31
 */

namespace bmwx591\privat24\request\properties;

use bmwx591\privat24\Object;

class MobileReplenishmentProperties extends Object implements PropertiesInterface
{
    private $phone;
    private $amt;

    public function getPhone()
    {
        $this->phone;
    }

    public function setPhone($phone)
    {
        if (!is_string($phone)) {
            throw new \InvalidArgumentException('"phone" must be a string');
        }
        $this->phone = $phone;
    }

    public function getAmt()
    {
        return $this->amt;
    }

    public function setAmt($amt)
    {
        if (!is_numeric($amt)) {
            throw new \InvalidArgumentException('"amt" must be a number');
        }
        $this->amt = $amt;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return [
            'phone' => urlencode($this->getPhone()),
            'amt' => urlencode($this->getAmt())
        ];
    }

    /**
     * @return bool
     */
    public function validate()
    {
        return !(empty($this->phone) || empty($this->amt));
    }
}
