<?php

/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 18.11.16
 * Time: 10:14
 */

namespace bmwx591\privat24\request;

use bmwx591\privat24\Client;

interface Request
{
    public function getUrl();

    public function getSignature();

    public function validate();

    public function setClient(Client $client);

    public function getAttributes();

    public function getHttpRequest();
}
