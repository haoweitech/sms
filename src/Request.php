<?php

namespace Hwtech\Sms;

class Request
{
    private $method;
    private $biz_content;

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getBizContent()
    {
        return $this->biz_content;
    }

    /**
     * @param array|string $biz_content
     * @return mixed
     */
    public function setBizContent($biz_content)
    {
        if (is_string($biz_content)) {
            $this->biz_content = $biz_content;
        } else if (is_array($biz_content)) {
            $this->biz_content = json_encode($biz_content, JSON_UNESCAPED_UNICODE);
        }
    }
}
