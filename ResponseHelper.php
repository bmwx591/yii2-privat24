<?php

namespace bmwx591\privat24;

use Psr\Http\Message\ResponseInterface;

class ResponseHelper
{
    public static function validate(ResponseInterface $response, $password)
    {
        $xml = simplexml_load_string($response->getBody()->getContents());
        $signature = (string) $xml->merchant->signature;
        $data = str_replace(['<data>', '</data>'], '', $xml->data->asXML());
        $dataSignature = sha1(md5($data . $password));
        return $signature === $dataSignature;
    }
}
