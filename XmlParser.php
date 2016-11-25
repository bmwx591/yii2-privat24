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
//        $res = json_decode(json_encode((array) simplexml_load_string($xml)),1);
//        return $res['logged']['@attributes'];
        if (!is_object($xml)) {
            $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        }

        $result = [];//new \stdClass();
        foreach ($xml as $el) {
//            print_r($el);die;
            if (is_object($el)) {
                if(isset($result[$el->getName()])) {
                    $result[] = [$el->getName() => [$result[$el->getName()]]];
                }
                $result[] = [$el->getName()] = $this->convertXmlToArray($el);
                if ($el->attributes()->count()) {
                    $attributes = (array)$el->attributes();
                    $result[$el->getName()]['_attributes'] = reset($attributes);
                }
                if (!$el->children()->count() && !empty((array) $el)) {
                    $result[$el->getName()]['_value'] = (array) $el;
                }

            }
//            print_r($el->getName());die;
        }
        return $result;
//        print_r($result);die;

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

//        if (!is_object($xml)) {
//            $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
//        }
//
//        $result = [];
//        if ($xml->children()) {
//            foreach ($xml->children() as $childValue) {
//                if (is_object($childValue)) {
////                    if (isset($result[$childValue->getName()])) {
////                        $result[$childValue->getName()][] = $this->convertXmlToArray($childValue);
////                    } else {
//                        $result[$childValue->getName()] = $this->convertXmlToArray($childValue);
////                    }
//                }
//                foreach ($childValue->attributes() as $attr => $attrVal) {
//                    $result[$childValue->getName()]['_attributes'][$attr] = (string) $attrVal;
//                }
//                if (!$childValue->count() && !empty((string) $childValue)) {
//                    $result[$childValue->getName()][] = (string) $childValue;//$this->convertXmlToArray($childValue);
//                }
//            }
//        } else {
//            $result = (string) $xml;
//        }
//        return $result;
    }
}
