<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 19.11.16
 * Time: 15:41
 */

namespace bmwx591\privat24\request;

use Yii;
use bmwx591\privat24\Client;
use bmwx591\privat24\request\properties\PropertiesInterface;
use yii\base\InvalidParamException;
use yii\base\InvalidValueException;

/**
 * Class Request
 * @package bmwx591\privat24\request
 *
 */
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

    public function __construct($config = [])
    {
        if ($config['properties']) {
            $this->setProperties($config['properties']);
            if (!$this->getProperties()->validate()) {
                throw new InvalidParamException('Illegal values for request options');
            }
            unset($config['properties']);
        }
//        if (!empty($config)) {
            Yii::configure($this, $config);
//        }
    }
    
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param string $operation
     * @return $this
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
        return $this;
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
     * @return $this
     */
    public function setWait($wait)
    {
        $this->wait = $wait;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getTest()
    {
        return is_null($this->test) ? $this->getClient()->getIsTest() : $this->test;
    }

    /**
     * @param boolean $test
     * @return $this
     */
    public function setTest($test)
    {
        $this->test = $test;
        return $this;
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
     * @return $this
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return integer
     */
    public function getMerchantId()
    {
        return $this->getClient()->getId();
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return PropertiesInterface
     */
    public function getProperties()
    {
        if (empty($this->properties)) {
            throw new InvalidValueException('Must set the properties to request that implements 
                yii\request\properties\PropertiesInterface');
        }
        return $this->properties;
    }

    /**
     * @return string
     */
    public function getSignature($data)
    {
        return sha1(md5($data . $this->getClient()->getPassword()));
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (empty($this->client)) {
            throw new InvalidValueException('Must set the client property');
        }
        return $this->client;
    }

    /**
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return array Request attributes
     */
    protected function getAttributes()
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
            [['paymentId', 'operation'], 'string']
        ]);
        $this->getProperties()->validate();
        return !($model->hasErrors() || $this->getProperties()->hasErrors());
    }

    /**
     * Prepare request content
     * @return $this
     */
    public function prepare()
    {
        $this->getClient()->getFormatter($this->getFormat())->formate($this);
        return $this;
    }
}
