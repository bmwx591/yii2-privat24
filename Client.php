<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 18.11.16
 * Time: 8:42
 */

namespace bmwx591\privat24;

use bmwx591\privat24\request\Request;
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
    const BASE_URL = 'https://api.privatbank.ua/p24api';

    private $id;
    private $password;
    private $isTest;

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
        if (!preg_match('/^[0-9a-zA-Z]$/', $password)) {
            throw new InvalidParamException('Illegal password value');
        }
        $this->password;
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

    public function send(Request $request)
    {
        if (!$request->validate()) {
            throw new InvalidParamException('Request is not valid');
        }
        $request->setClient($this);
        $httpClient = new HttpClient([
            'baseUrl' => self::BASE_URL,
            'transport' => CurlTransport::class,
            'requestConfig' => [
                'url' => $request->getUrl(),
                'data' => $request->get
            ]
        ]);
        $httpClient->send($request->getHttpRequest());
    }
}
