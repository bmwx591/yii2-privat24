<?php

namespace bmwx591\privat24\request;

use bmwx591\privat24\ClientInterface;
use bmwx591\privat24\FormatterInterface;
use bmwx591\privat24\Object;
use bmwx591\privat24\request\properties\PropertiesInterface;
use bmwx591\privat24\SignatureHelper;
use InvalidArgumentException;

/**
 * Class Request
 * @package bmwx591\privat24\request
 *
 */
abstract class PayRequest extends Object implements RequestInterface
{

    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    const OPERATION_TYPE_CMT = 'cmt';
    const OPERATION_TYPE_PRP = 'prp';

    protected $url;
    protected $client;
    protected $operation = self::OPERATION_TYPE_CMT;
    protected $wait = 0;
    protected $test;
    protected $paymentId;
    protected $properties;
    protected $content;
    protected $format = ClientInterface::FORMAT_XML;
    protected $method = self::METHOD_GET;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return PayRequest
     * @throws InvalidArgumentException
     */
    public function setUrl($url)
    {
        if (!is_string($url)) {
            throw new InvalidArgumentException('url must be string');
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
     * @return PayRequest
     * @throws InvalidArgumentException
     */
    public function setOperation($operation)
    {
        if (!is_string($operation)) {
            throw new InvalidArgumentException('operation must be string');
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
     * @return PayRequest
     * @throws InvalidArgumentException
     */
    public function setWait($wait)
    {
        if (!is_int($wait) || $wait < 0 || $wait > 90) {
            throw new InvalidArgumentException('wait must be integer (0-90) seconds');
        }
        $this->wait = $wait;
        return $this;
    }

    /**
     * @return bool
     */
    public function getTest()
    {
        return is_null($this->test) ? $this->getClient()->getIsTest() : $this->test;
    }

    /**
     * @param bool $test
     * @return PayRequest
     * @throws InvalidArgumentException
     */
    public function setTest($test)
    {
        if (!is_bool($test)) {
            throw new InvalidArgumentException('test must be boolean');
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
     * @return PayRequest
     * @throws InvalidArgumentException
     */
    public function setPaymentId($paymentId)
    {
        if (!is_string($paymentId)) {
            throw new InvalidArgumentException('paymentId must be string');
        }
        $this->paymentId = $paymentId;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return PayRequest
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
     * @return PayRequest
     * @throws InvalidArgumentException
     */
    public function setFormat($format)
    {
        if (!is_string($format)) {
            throw new InvalidArgumentException('format must be string');
        }
        $this->format = $format;
        return $this;
    }

    /**
     * @return PropertiesInterface
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Set request properties
     * @param array $properties
     * @throws InvalidArgumentException
     */
    public function setProperties(array $properties = [])
    {
        $properties = $this->getPropertiesInstance($properties);
        if (!$properties instanceof PropertiesInterface) {
            throw new InvalidArgumentException('Properties must implements PropertiesInterface');
        }
        if (!$properties->validate()) {
            throw new InvalidArgumentException('Properties in not valid');
        }
        $this->properties = $properties;
    }

    /**
     * @return PropertiesInterface Properties instance
     */
    abstract protected function getPropertiesInstance(array $properties);

    /**
     * @return string
     */
    public function getSignature($data)
    {
        return SignatureHelper::calculate($data, $this->getClient()->getPassword());
    }

    /**
     * @return ClientInterface
     * @throws InvalidArgumentException
     */
    public function getClient()
    {
        if (empty($this->client)) {
            throw new InvalidArgumentException('Must set the client property');
        }
        return $this->client;
    }

    /**
     * @param ClientInterface $client
     * @return PayRequest
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get request method
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set request method
     * @param string $method
     * @throws InvalidArgumentException
     */
    public function setMethod($method)
    {
        if (!is_string($method)) {
            throw new InvalidArgumentException('method must be string');
        }
        $this->method = $method;
    }

    /**
     * Prepare request content
     */
    public function prepare()
    {
        $formatter = $this->getClient()->getFormatter($this->format);
        if (! $formatter instanceof FormatterInterface) {
            throw new InvalidArgumentException('Invalid formatter type');
        }
        $formatter->formate($this);
    }

    /**
     * @return bool
     */
    public function validate()
    {
        return !($this->getMerchantId() || $this->wait || $this->test || $this->paymentId || $this->properties);
    }
}
