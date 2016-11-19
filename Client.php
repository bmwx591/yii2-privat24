<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 18.11.16
 * Time: 8:42
 */

namespace bmwx591\privat24;

use bmwx591\privat24\request\RequestInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\base\Object;
use \yii\httpclient\Client as HttpClient;
use yii\httpclient\CurlTransport;

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
    
    public $baseUrl = 'https://api.privatbank.ua/p24api';
    public $formatters = [
        self::FORMAT_XML => 'bmwx591\privat24\XmlFormatter'
    ];
    private $id;
    private $password;
    private $isTest = false;

    public function init()
    {
        if (!isset($this->id, $this->password)) {
            throw new InvalidConfigException('');
        }
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
     * @throws InvalidParamException
     */
    public function setId($id)
    {
        if (!is_int($id) || $id < 0) {
            throw new InvalidParamException('Id must be integer');
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
     * @throws InvalidParamException
     */
    public function setPassword($password)
    {
        if (!preg_match('/^[0-9a-zA-Z]{32}$/', $password)) {
            throw new InvalidParamException('Illegal password value');
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
            throw new InvalidParamException('Parametr must be boolean');
        }
        $this->isTest = $isTest;
    }
    
    public function getFormatter($format)
    {
        if (!isset($this->formatters[$format])) {
            throw new InvalidParamException("Unrecognized format '{$format}'");
        }
        if (!is_object($this->formatters[$format])) {
            $this->formatters[$format] = Yii::createObject($this->formatters[$format]);
        }
        return $this->formatters[$format];
    }

    public function send(RequestInterface $request)
    {
        $request->setClient($this);
        if (!$request->validate()) {
            throw new InvalidParamException('Request is not valid');
        }
        $request->prepare();
        $httpClient = new HttpClient([
            'baseUrl' => $this->baseUrl,
            'transport' => CurlTransport::class,
            'requestConfig' => [
                'url' => $request->getUrl(),
                'content' => $request->getContent()
            ]
        ]);
        $response = $httpClient->createRequest()->send();
        var_dump($response);die;
    }
}
