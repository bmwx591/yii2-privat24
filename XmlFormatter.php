<?php

namespace bmwx591\privat24;

use bmwx591\privat24\request\properties\PropertiesInterface;
use bmwx591\privat24\request\RequestInterface;

class XmlFormatter implements FormatterInterface
{

    public $version = '1.0';
    public $encoding = 'utf-8';
    public $rootTag = 'request';

    public function formate(RequestInterface $request)
    {
        $dom = new \DOMDocument($this->version, $this->encoding);
        $root = $dom->createElement($this->rootTag);
        $root->setAttribute('version', $this->version);
        $dom->appendChild($root);
        $data = $dom->createElement('data');
        $root->appendChild($data);
        $data->appendChild($dom->createElement('oper', $request->getOperation()));
        $data->appendChild($dom->createElement('wait', $request->getWait()));
        $data->appendChild($dom->createElement('test', (int) $request->getTest()));
        $payment = $dom->createElement('payment');
        $paymentId = $request->getPaymentId();
        if (isset($paymentId)) {
            $payment->setAttribute('id', $paymentId);
        }
        $data->appendChild($payment);
        $properties = $request->getProperties();
        if ($properties instanceof PropertiesInterface) {
            foreach ($properties->getValues() as $name => $value) {
                $prop = $dom->createElement('prop');
                $prop->setAttribute('name', $name);
                $prop->setAttribute('value', $value);
                $payment->appendChild($prop);
            }
        }
        $merchant = $dom->createElement('merchant');
        $merchant->appendChild($dom->createElement('id', $request->getMerchantId()));
        $signatureData = str_replace(['<data>', '</data>'], '', $dom->saveXML($data));
        $merchant->appendChild($dom->createElement('signature', $request->getSignature($signatureData)));
        $root->insertBefore($merchant, $data);
        $request->setContent($dom->saveXML());
    }
}
