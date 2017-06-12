<?php

namespace Blog\Service;

class ContentResponse
{
    const CODE_OK = 200;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var string|null
     */
    protected $html;

    /**
     * @var string|null
     */
    protected $error;

    /**
     * ContentResponse constructor.
     * @param string $code
     * @param null|string $html
     * @param null|string $error
     */
    public function __construct(string $code, string $html = null, string $error = null)
    {
        $this->code = (int) $code;
        $this->html = $html;
        $this->error = $error;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return null|string
     */
    public function getHtml(): ?string
    {
        return $this->html;
    }

    /**
     * @return null|string
     */
    public function getError(): ?string
    {
        return $this->error;
    }
}
