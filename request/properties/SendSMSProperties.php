<?php

namespace bmwx591\privat24\request\properties;

use bmwx591\privat24\Object;

/**
 * Class SendSMSProperties
 * @package bmwx591\privat24\request\properties
 *
 * @property string $phone Phone number
 * @property string $phoneTo Phone which will be sent sms
 * @property string $text Sms text
 */
class SendSMSProperties extends Object implements PropertiesInterface
{
    private $phone;
    private $phoneTo;
    private $text;

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        if (!is_string($phone)) {
            throw new \InvalidArgumentException('"phone" must be a string');
        }
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPhoneTo()
    {
        return $this->phoneTo;
    }

    /**
     * @param string $phoneTo
     */
    public function setPhoneTo($phoneTo)
    {
        if (!is_string($phoneTo)) {
            throw new \InvalidArgumentException('"phoneTo" must be a string');
        }
        $this->phoneTo = $phoneTo;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        if (!is_string($text)) {
            throw new \InvalidArgumentException('"text" must be a string');
        }
        $this->text = $text;
    }

    /**
     * @return array Properties for request
     */
    public function getValues()
    {
        return [
            'phone' => urlencode($this->getPhone()),
            'phoneto' => urlencode($this->getPhoneTo()),
            'text' => urlencode($this->getText())
        ];
    }

    /**
     * @inheritdoc
     */
    public function validate()
    {
        return !(empty($this->phone) || empty($this->phoneTo) || empty($this->text));
    }
}
