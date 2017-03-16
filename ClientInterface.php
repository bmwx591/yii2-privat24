<?php

namespace bmwx591\privat24;

use bmwx591\privat24\request\RequestInterface;
use bmwx591\privat24\response\ResponseInterface;

interface ClientInterface
{
    const FORMAT_XML = 'xml';
    const FORMAT_JSON = 'json';

    /**
     * @return integer Merchant id
     */
    public function getId();

    /**
     * @return string Merchant password
     */
    public function getPassword();

    /**
     * @param string $formatter
     * @return FormatterInterface Formatter instance that formats the request
     */
    public function getFormatter($formatter = self::FORMAT_XML);

    /**
     * @return bool
     */
    public function getIsTest();

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function send(RequestInterface $request);
}
