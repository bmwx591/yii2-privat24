<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 19.11.16
 * Time: 15:41
 */

namespace bmwx591\privat24\request;


use bmwx591\privat24\Client;
use bmwx591\privat24\request\properties\PropertiesInterface;

abstract class Request implements RequestInterface
{
    protected $client;
    protected $operation = 'cmt';
    protected $wait = 0;
    protected $test;
    protected $paymentId;
    protected $properties;
    protected $propertiesClass;
    protected $content;
    protected $format = Client::FORMAT_XML;

    public function __construct($config = [], $properties = [])
    {
        if (!empty($properties)) {
            if (!isset($properties['class'])) {
                $properties['class'] = $this->propertiesClass;
            }
            $config['properties'] = $properties;
        }
        if (!empty($config)) {
            Yii::configure($this, $config);
        }
    }
    
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function setOperation($operation)
    {
        $this->operation = $operation;
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
        return is_null($this->test) ? $this->client->getIsTest() : $this->test;
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

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getMerchantId()
    {
        return $this->client->getId();
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function setProperties(PropertiesInterface $properties)
    {
        $this->properties = $properties;
    }

    public function getProperties()
    {
        return $this->properties->getAttributes();
    }

    /**
     * @return string
     */
    public function getSignature($data)
    {
        return sha1(md5($data . $this->client->getPassword()));
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
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
            'operation' => $this->getOperation()
        ];
    }

    /**
     * @return boolean
     */
    public function validate()
    {
        $model =  DynamicModel::validateData($this->getAttributes(), [
            [['wait', 'test', 'paymentId', 'operation'], 'required'],
            ['wait', 'integer', 'min' => 0, 'max' => 90],
            ['test', 'boolean', 'trueValue' => true, 'falseValue' => false, 'strict' => true],
            [['paymentId', 'operation'], 'string', 'encoding' => 'UTF-8']
        ]);
        $this->properties->validate();
        return !$model->hasErrors() && !$this->properties->hasErrors();
    }

    public function prepare()
    {
        $this->client->getFormatter($this->getFormat())->formate($this);
    }
}