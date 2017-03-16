<?php

namespace bmwx591\privat24;

use bmwx591\privat24\response\Node;
use bmwx591\privat24\response\Response;
use bmwx591\privat24\response\ResponseInterface;

class XmlParser implements ParserInterface
{
    /**
     *
     * @param string $content
     * @return ResponseInterface
     */
    public function parse($content)
    {
        $response = new Response();
        if (!empty($content)) {
            $response->setContent($this->convertXmlToArray($content));
        }
        return $response;
    }

    /**
     * Parse xml to nodes
     * @param string $xml
     * @param string $name
     * @return Node
     */
    protected function convertXmlToArray($xml, $name = 'root')
    {
        if (!is_object($xml)) {
            $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        $xml = (array) $xml;
        $node = new Node($name);
        foreach ($xml as $key => $el) {
            $this->parseElement($el, $key, $node);
        }
        return $node;
    }

    /**
     * @param \SimpleXMLElement|array|string $el
     * @param string $key
     * @param Node $node
     */
    private function parseElement($el, $key, $node)
    {
        $values = [];
        if (is_object($el)) {
            $values[$key] = $this->convertXmlToArray($el, $key);
        }
        elseif (is_array($el) && '@attributes' != $key) {
            foreach ($el as $k => $v) {
                if (is_object($v)) {
                    $values[$k] = $this->convertXmlToArray($v, $key);
                } else {
                    $values[$k] = $v;
                }
            }
        }
        else {
            if ('@attributes' == $key) {
                $node->setAttributes($el);
            } else {
                $values[$key] = $el;
            }
        }
        $node->setValue($values);
    }
}
