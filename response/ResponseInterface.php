<?php

namespace bmwx591\privat24\response;

interface ResponseInterface
{

    /**
     * Set response content
     * @param mixed $content
     */
    public function setContent($content);

    /**
     * Get response content
     */
    public function getContent();
}