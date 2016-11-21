<?php

/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 18.11.16
 * Time: 22:43
 */

namespace bmwx591\privat24\request\properties;

use yii\base\Model;

/**
 * Class SendSMSProperties
 * @package bmwx591\privat24\request\properties
 *
 * @property string $phone Phone number
 * @property string $phoneTo Phone which will be sent sms
 * @property string $text Sms text
 */
class SendSMSProperties extends Model implements PropertiesInterface
{
    protected $phone;
    protected $phoneTo;
    protected $text;

    public function rules()
    {
        return [
            [['phone', 'phoneTo', 'text'], 'required'],
            ['text', 'string'],
            [['phone', 'phoneTo'], 'string', 'max' => 20]
        ];
    }

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
        $this->text = $text;
    }

    /**
     * @return array Properties for request
     */
    public function getAttributes()
    {
        return [
            'phone' => urlencode($this->getPhone()),
            'phoneto' => urlencode($this->getPhoneTo()),
            'text' => urlencode($this->getText())
        ];
    }
}
