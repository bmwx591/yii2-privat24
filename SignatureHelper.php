<?php

namespace bmwx591\privat24;

use Psr\Http\Message\ResponseInterface;

class SignatureHelper
{
    public static function validate($response, $password)
    {
        $xml = simplexml_load_string($response);
        $signature = (string) $xml->merchant->signature;
        $data = str_replace(['<data>', '</data>'], '', $xml->data->asXML());
        $dataSignature = self::calculate($data, $password);
        return $signature === $dataSignature;
    }

    public static function calculate($data, $password)
    {
        return sha1(md5($data . $password));
    }
}
