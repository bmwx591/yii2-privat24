<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 19.11.16
 * Time: 15:41
 */

namespace bmwx591\privat24\request;

use bmwx591\privat24\Client;
use bmwx591\privat24\Object;
use bmwx591\privat24\request\properties\PropertiesInterface;
use bmwx591\privat24\SignatureHelper;

/**
 * Class Request
 * @package bmwx591\privat24\request
 *
 */
abstract class Request extends Object implements RequestInterface
{
    private $client;
    private $operation = 'cmt';
    private $wait = 0;
    private $test;
    private $paymentId;
    private $properties;
    private $content;
    private $format = Client::FORMAT_XML;
    private $method = 'get';
    
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
        if (!is_string($url)) {
            throw new \BadMethodCallException('url must be string');
        }
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
        if (!is_string($operation)) {
            throw new \BadMethodCallException('operation must be string');
        }
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
        if (!is_int($wait) || $wait < 0 || $wait > 90) {
            throw new \BadMethodCallException('wait must be integer (0-90) seconds');
        }
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
        if (!is_bool($test)) {
            throw new \BadMethodCallException('test must be boolean');
        }
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
        if (!is_string($paymentId)) {
            throw new \BadMethodCallException('paymentId must be string');
        }
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
        if (!is_string($format)) {
            throw new \BadMethodCallException('format must be string');
        }
        $this->format = $format;
        return $this;
    }

    /**
     * @return PropertiesInterface
     */
    public function getProperties()
    {
        if (empty($this->properties)) {
            throw new \BadMethodCallException('Must implements yii\request\properties\PropertiesInterface');
        }
        return $this->properties;
    }

    public function setProperties($properties = [])
    {
        $properties = $this->getPropertiesInstance($properties);
        if (!$properties instanceof PropertiesInterface) {
            throw new \BadMethodCallException('Properties must implements yii\request\properties\PropertiesInterface');
        }
        $this->properties = $properties;
    }

    abstract protected function getPropertiesInstance($properties);

    /**
     * @return string
     */
    public function getSignature($data)
    {
        return SignatureHelper::calculate($data, $this->getClient()->getPassword());
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (empty($this->client)) {
            throw new \BadMethodCallException('Must set the client property');
        }
        return $this->client;
    }

    /**
     * @param Client $client
     * @return Request
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        if (!is_string($method)) {
            throw new \BadMethodCallException('method must be string');
        }
        $this->method = $method;
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
