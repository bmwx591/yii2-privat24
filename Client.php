<?php

namespace bmwx591\privat24;

use bmwx591\privat24\request\RequestInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client as HttpClient;

/**
 * Class Client
 * @package bmwx591\privat24
 *
 * @property integer $id Merchant id
 * @property string $password Merchant password
 * @property boolean $isTest is test request
 */
class Client extends Object
{
    const FORMAT_XML = 'xml';
    
    private $baseUrl = 'https://api.privatbank.ua/p24api/';
    private $formatters = [
        self::FORMAT_XML => 'bmwx591\privat24\XmlFormatter'
    ];
    private $responseFormats = [
        self::FORMAT_XML => 'bmwx591\privat24\XmlParser'
    ];
    private $http;
    private $id;
    private $password;
    private $isTest = false;

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        if (!is_string($baseUrl)) {
            throw new \BadMethodCallException('baseUrl must be string');
        }
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @throws \BadMethodCallException
     */
    public function setId($id)
    {
        if (!is_int($id) || $id < 1) {
            throw new \BadMethodCallException('Id must be integer');
        }
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @throws \BadMethodCallException
     */
    public function setPassword($password)
    {
        if (!is_string($password) || !preg_match('/^[0-9a-zA-Z]{32}$/', $password)) {
            throw new \BadMethodCallException('Illegal password value');
        }
        $this->password = $password;
    }

    /**
     * @return boolean
     */
    public function getIsTest()
    {
        return $this->isTest;
    }

    /**
     * @param boolean $isTest
     */
    public function setIsTest($isTest)
    {
        if (!is_bool($isTest)) {
            throw new \BadMethodCallException('Parametr must be boolean');
        }
        $this->isTest = $isTest;
    }
    
    public function getFormatter($format)
    {
        if (!isset($this->formatters[$format])) {
            throw new \BadMethodCallException("Unrecognized format '{$format}'");
        }
        $formatter = $this->formatters[$format];
        if (is_string($formatter)) {
            $this->formatters[$format] = new $formatter();
        }
        return $this->formatters[$format];
    }


    public function getParser($format)
    {
        if (!isset($this->parsers[$format])) {
            throw new \BadMethodCallException("Unrecornized format '{$format}'");
        }
        $parser = $this->parsers[$format];
        if (is_string($parser)) {
            $this->parsers[$format] = new $parser();
        }
        return $this->parsers[$format];
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if (empty($this->http)) {
            $this->http = new HttpClient();
        }
        return $this->http;
    }

    /**
     * @param ClientInterface $http
     */
    public function setHttpClient(ClientInterface $http)
    {
        $this->http = $http;
    }

    protected function getHttpRequest(RequestInterface $request)
    {
        return new Request($request->getMethod(), $request->getUrl(), [
            'Content-Type' => 'application/xml; charset=utf-8'
        ], $request->getContent());
    }

    public function getFormatters()
    {
        return $this->formatters;
    }

    public function send(RequestInterface $request)
    {
        $parser = new XmlParser();
        $content = '<?xml version="1.0" encoding="UTF-8"?>
            <response version="1.0">
                <merchant>
                    <id>75482</id>
                    <signature>553995c5ccc8c81815b58cf6374f68f00a28bbd7</signature>
                </merchant>
                <data>
                    <oper>cmt</oper>
                    <info>
                        <statements status="excellent" credit="0.0" debet="0.3"  >
                            <statement card="5168742060221193" appcode="591969" trandate="2013-09-02" amount="0.10 UAH" cardamount="-0.10 UAH" rest="0.95 UAH" terminal="Пополнение мобильного +380139917053 через «Приват24»" description="" />
                            <statement card="5168742060221193" appcode="991794" trandate="2013-09-02" amount="0.10 UAH" cardamount="-0.10 UAH" rest="1.05 UAH" terminal="Пополнение мобильного +380139917035 через «Приват24»" description="" />
                            <statement card="5168742060221193" appcode="801111" trandate="2013-09-02" amount="0.10 UAH" cardamount="-0.10 UAH" rest="1.15 UAH" terminal="Пополнение мобильного +380139910008 через «Приват24»" description="" />
                        </statements>
                    </info>
                </data>
            </response>
        ';
        return $parser->parse($content);
        $request->setClient($this)->prepare();
        $httpRequest = $this->getHttpRequest($request);
        $response = $this->getHttpClient()->send($httpRequest, ['base_uri' => $this->baseUrl]);
        $responseContent = $response->getBody()->getContents();
        if (SignatureHelper::validate($responseContent, $this->getPassword())) {
            return $this->getParser($request->getFormat())->parse($responseContent);
        }
        throw new \Exception('Response is not valid');
    }
}
