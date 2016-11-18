<?php

/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 18.11.16
 * Time: 10:29
 */

namespace bmwx591\privat24\request;

use bmwx591\privat24\Client;
use yii\base\DynamicModel;
use yii\httpclient\Request as HttpRequest;

/**
 * Class SendSMSRequest
 * @package bmwx591\privat24\request
 *
 * @property Client $client;
 */
class SendSMSRequest implements Request
{
    public $client;
    protected $url = 'sendsms';
    protected $wait;
    protected $test;
    protected $paymentId;
    protected $phone;
    protected $phoneTo;
    protected $text;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return integer
     */
    public function getWait()
    {
        return $this->wait;
    }

    /**
     * @param integer $wait
     */
    public function setWait($wait)
    {
        $this->wait = $wait;
    }

    /**
     * @return boolean
     */
    public function getTest()
    {

        return empty($this->test) ? $this->client->getIsTest() : $this->test;
    }

    /**
     * @param boolean $test
     */
    public function setTest($test)
    {
        $this->test = $test;
    }

    /**
     * @return string
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @param string $paymentId
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;
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
     * @return string
     */
    public function getSignature()
    {
        $data = $this->getSignatureData();
        return sha1(md5($data . $this->client->getPassword()));
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    protected function getSignatureData()
    {
        return '
            <oper>cmt</oper>
            <wait>'. $this->getWait() .'</wait>
            <test>'. $this->getTest().'</test>
            <payment id="'. $this->getPaymentId() .'">
                <prop name="phone" value="'. urlencode($this->getPhone()) .'" />
                <prop name="phoneto" value="'. urlencode($this->getPhoneTo()) .'" />
                <prop name="text" value="'. $this->getText() .'" />
            </payment>
        ';
    }

    /**
     * @return array Request attributes
     */
    public function getAttributes()
    {
        return [
            'wait' => $this->getWait(),
            'test' => $this->getTest(),
            'paymentId' => $this->getPaymentId(),
            'phone' => $this->getPhone(),
            'phoneTo' => $this->getPhoneTo(),
            'text' => $this->getText()
        ];
    }

    /**
     * @return boolean
     */
    public function validate()
    {
        $model =  DynamicModel::validateData($this->getAttributes(), [
            [['wait', 'test', 'paymentId', 'phone', 'phoneTo'], 'required'],
            ['wait', 'integer', 'min' => 1, 'max' => 90],
            ['paymentId', 'integer'],
            ['test', 'boolean', 'trueValue' => 1, 'falseValue' => 0, 'strict' => true],
            [['paymentId', 'phone', 'phoneTo', 'text'], 'string']
        ]);
        return !$model->hasErrors();
    }

    public function getHttpRequest()
    {
        return new HttpRequest([
            'url' => self::getUrl(),
            'data' => $this->getAttributes()
        ]);
    }
}
