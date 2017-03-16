<?php

/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 18.11.16
 * Time: 10:29
 */

namespace bmwx591\privat24\request;

use bmwx591\privat24\request\properties\SendSMSProperties;

/**
 * Class SendSMSRequest
 * @package bmwx591\privat24\request
 */
class SendSMSPayRequest extends PayRequest
{
    protected $url = 'sendsms';
    protected $method = self::METHOD_POST;

    /**
     * @param array $properties Request properties
     * @return SendSMSProperties
     */
    protected function getPropertiesInstance(array $properties)
    {
        return new SendSMSProperties($properties);
    }
}
