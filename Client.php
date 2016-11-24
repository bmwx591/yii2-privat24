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
    private $parsers = [
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
        $content = '
        <account_order>
                <unsupport/>
                <logged sessioncount="0" visitscount="0"/>
                <locale language="ru">
                    <date id="20140827T18:23:40" traditional="27.08.2014">27 авг 2014,Ср 18:23:40</date>
                </locale>
                <request url_base="http://privat24.privatbank.ua/p24/" url="/accountorder" os="Win" win="Y" ie="N"/>
                <info>
                    <dump PUREXML="" city="Киев" oper="prp" type="обувь" bonus=""/>
                </info>
                <bonus>
                    <bonus name="Магазин \'Супер Стиль\'" address="Пр.Победы,136" type="обувь" bonus_plus="5.00" state="Киевская" city="Киев" tel="8050 423-88-51" work_time="10:00-21:00" country="UA"/>
                    <bonus name="Маг. \'Валекс\'" address="г. Киев, ул. Мишуги, 3в" type="обувь" bonus_plus="5.00" state="Киевская" city="Киев" tel="0445552437" work_time="10:00-20:00" country="UA"/>
                    <bonus name="Магазин обуви \'Валекс\'" address="г. Киев, ул. Строителей, 32/2" type="обувь" bonus_plus="5.00" state="Киевская" city="Киев" tel="0445598509" work_time="10:00-22:00" country="UA"/>
                    <bonus name="М-н \'Взуття для життя\'" address="г.Киев, ул. Братиславская, 26" type="Обувь" bonus_plus="5.00" state="Киевская" city="Киев" tel="80443610558" work_time="10:00-20:00" country="UA"/>
                    <bonus name="М.Обувь для жизни" address="ул.Щусева, 2/19" type="розничная торговля обувью" bonus_plus="5.00" state="Киевская" city="Киев" tel="8050-353-13-94" work_time="10:00-20:00" country="UA"/>
                </bonus>
            </account_order>
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
