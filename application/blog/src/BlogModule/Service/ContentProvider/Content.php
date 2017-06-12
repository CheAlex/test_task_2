<?php

namespace BlogModule\Service\ContentProvider;

class Content implements ContentInterface
{
    /**
     * @var int
     */
    protected $code;

    /**
     * @var string|null
     */
    protected $content;

    /**
     * @var string|null
     */
    protected $error;

    /**
     * ContentResponse constructor.
     * @param string $code
     * @param null|string $content
     * @param null|string $error
     */
    public function __construct(string $code, string $content = null, string $error = null)
    {
        $this->code = (int) $code;
        $this->content = $content;
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
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return null|string
     */
    public function getError(): ?string
    {
        return $this->error;
    }
}
