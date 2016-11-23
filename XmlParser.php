<?php

namespace bmwx591\privat24;

use Psr\Http\Message\ResponseInterface;

class XmlParser implements ParserInterface
{
    public function parse(ResponseInterface $response)
    {
        var_dump($response->getBody()->getContents());die;
        return $this->convertXmlToArray($response->getBody()->getContents());
    }

    protected function convertXmlToArray($xml)
    {
        var_dump($xml);die;
        if (!is_object($xml)) {
            $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        $result = (array) $xml;
        foreach ($result as $key => $value) {
            if (is_object($value)) {
                $result[$key] = $this->convertXmlToArray($value);
            }
        }
        return $result;
    }
}
