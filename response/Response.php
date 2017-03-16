<?php

namespace bmwx591\privat24\response;

class Response implements ResponseInterface
{
    private $content;

    /**
     * @inheritdoc
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @inheritdoc
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

}
