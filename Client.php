<?php

namespace bmwx591\privat24;

use bmwx591\privat24\request\RequestInterface;
use bmwx591\privat24\response\ResponseInterface;
use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;

/**
 * Class Client
 * @package bmwx591\privat24
 *
 * @property string $baseUrl Base api url
 * @property array $formatters Formatters list
 * @property array $parsers Parsers list
 * @property ClientInterface $http Http client
 * @property integer $id Merchant id
 * @property string $password Merchant password
 * @property boolean $isTest is test request
 */
class Client extends Object implements \bmwx591\privat24\ClientInterface
{

    private $baseUrl = 'https://api.privatbank.ua/p24api/';
    private $formatters = [
        self::FORMAT_XML => XmlFormatter::class
    ];
    private $parsers = [
        self::FORMAT_XML => XmlParser::class
    ];
    private $http;
    private $id;
    private $password;
    private $isTest = false;

    /**
     * @param string $baseUrl
     * @throws InvalidArgumentException
     */
    public function setBaseUrl($baseUrl)
    {
        if (!is_string($baseUrl)) {
            throw new InvalidArgumentException('"baseUrl" must be string');
        }
        $this->baseUrl = $baseUrl;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @throws InvalidArgumentException
     */
    public function setId($id)
    {
        if (!is_int($id) || $id < 1) {
            throw new InvalidArgumentException('"Id" must be integer');
        }
        $this->id = $id;
    }

    /**
     * @inheritdoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @throws InvalidArgumentException
     */
    public function setPassword($password)
    {
        if (!is_string($password) || !preg_match('/^[0-9a-zA-Z]{32}$/', $password)) {
            throw new InvalidArgumentException('Illegal password value');
        }
        $this->password = $password;
    }

    /**
     * @inheritdoc
     */
    public function getIsTest()
    {
        return $this->isTest;
    }

    /**
     * @param boolean $isTest
     * @throws InvalidArgumentException
     */
    public function setIsTest($isTest)
    {
        if (!is_bool($isTest)) {
            throw new InvalidArgumentException('Parameter must be boolean');
        }
        $this->isTest = $isTest;
    }

    /**
     * @param string $format
     * @return FormatterInterface
     * @throws InvalidArgumentException
     */
    public function getFormatter($format = self::FORMAT_XML)
    {
        if (!isset($this->formatters[$format])) {
            throw new InvalidArgumentException("Unrecognized format '{$format}'");
        }
        $formatter = $this->formatters[$format];
        if (is_string($formatter)) {
            $this->formatters[$format] = new $formatter();
        }
        return $this->formatters[$format];
    }

    /**
     * @param string $format
     * @return ParserInterface
     * @throws InvalidArgumentException
     */
    protected function getParser($format)
    {
        if (!isset($this->parsers[$format])) {
            throw new InvalidArgumentException("Unrecornized format '{$format}'");
        }
        $parser = $this->parsers[$format];
        if (is_string($parser)) {
            $this->parsers[$format] = new $parser();
        }
        return $this->parsers[$format];
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        if (empty($this->http)) {
            $this->http = new HttpClient([
                'base_uri' => $this->baseUrl
            ]);
        }
        return $this->http;
    }

    /**
     * @param RequestInterface $request
     * @return Request
     */
    protected function getHttpRequest(RequestInterface $request)
    {
        return new Request($request->getMethod(), $request->getUrl(), [
            'Content-Type' => 'application/xml; charset=utf-8'
        ], $request->getContent());
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function send(RequestInterface $request)
    {
        $request->setClient($this);
        if (!$request->validate()) {
            throw new InvalidArgumentException('Request in not valid');
        }
        $request->prepare();
        $httpRequest = $this->getHttpRequest($request);
        $httpResponse = $this->getHttpClient()->send($httpRequest);
        $responseContent = $httpResponse->getBody()->getContents();
        if (SignatureHelper::validate($responseContent, $this->getPassword())) {
            $parser = $this->getParser($request->getFormat());
            if ($parser instanceof ParserInterface) {
                return $parser->parse($responseContent);
            }
            throw new InvalidArgumentException('Invalid parser type');
        }
        throw new Exception('Response is not valid');
    }
}
