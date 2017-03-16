<?php

namespace bmwx591\privat24\request;

use bmwx591\privat24\ClientInterface;

interface RequestInterface
{
    public function prepare();

    public function getUrl();

    public function getFormat();

    public function getMethod();

    public function getOperation();

    public function getWait();

    public function getTest();

    public function getPaymentId();

    public function getContent();

    public function setContent($content);

    public function getMerchantId();

    public function getProperties();

    public function setProperties(array $properties = []);

    public function getSignature($data);

    public function setClient(ClientInterface $client);

    public function validate();

}
