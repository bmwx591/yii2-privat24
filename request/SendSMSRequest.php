<?php

/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 18.11.16
 * Time: 10:29
 */

namespace bmwx591\privat24\request;

use bmwx591\privat24\request\properties\PropertiesInterface;
use bmwx591\privat24\request\properties\SendSMSProperties;
use Yii;

/**
 * Class SendSMSRequest
 * @package bmwx591\privat24\request
 */
class SendSMSRequest extends Request
{
    protected $url = 'sendsms';

    /**
     * @param array $properties Request properties
     * @return SendSMSProperties
     */
    protected function getPropertiesInstance($properties)
    {
        return new SendSMSProperties($properties);
    }
}
