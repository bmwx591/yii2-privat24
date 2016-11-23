<?php

namespace bmwx591\privat24;

use Psr\Http\Message\ResponseInterface;

interface ParserInterface
{
    public function parse(ResponseInterface $response);
}
