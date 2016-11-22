<?php

/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 18.11.16
 * Time: 10:14
 */

namespace bmwx591\privat24\request;

use bmwx591\privat24\Client;
use bmwx591\privat24\request\properties\PropertiesInterface;

interface RequestInterface
{
    public function prepare();
    
    public function getUrl();

    public function getOperation();

    public function getWait();

    public function getTest();

    public function getPaymentId();

    public function getContent();

    public function setContent($content);

    public function getMerchantId();

    public function getProperties();

    public function setProperties($properties = []);

    public function getSignature($data);

    public function setClient(Client $client);

}
