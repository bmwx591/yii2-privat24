<?php

namespace bmwx591\privat24\response;

class Response implements ResponseInterface
{
    private $content;

    /**
     * @inheridoc
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     *
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

}
