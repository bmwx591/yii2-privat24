<?php

namespace bmwx591\privat24;

use Psr\Http\Message\ResponseInterface;

class XmlParser implements ParserInterface
{
    public function parse($response)
    {
        return $this->convertXmlToArray($response);
    }

    protected function convertXmlToArray($xml)
    {
//        if (!is_object($xml)) {
//            $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
//        }
//        $result = (array) $xml;
//        foreach ($result as $key => $value) {
//            if (is_object($value)) {
//                $result[$key] = $this->convertXmlToArray($value);
//            }
//        }
//        return $result;



        if (!is_object($xml)) {
            $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        }

        $result = [];
        if ($xml->children()) {
            foreach ($xml->children() as $childValue) {
                if (is_object($childValue)) {
                    if (isset($result[$childValue->getName()])) {
                        $result[$childValue->getName()][] = $this->convertXmlToArray($childValue);
                    } else {
                        $result[$childValue->getName()] = $this->convertXmlToArray($childValue);
                    }
                }
                foreach ($childValue->attributes() as $attr => $attrVal) {
                    $result[$childValue->getName()]['attributes'][$attr] = (string) $attrVal;
                }
                if (!$childValue->count() && !empty((string) $childValue)) {
                    $result[$childValue->getName()][] = (string) $childValue;
                }
            }
        } else {
            $result = (string) $xml;
        }
        return $result;
    }
}
