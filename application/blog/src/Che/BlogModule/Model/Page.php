<?php

namespace Che\BlogModule\Model;

/**
 * Class Page
 *
 * @package Che\BlogModule\Model
 */
class Page implements PageInterface
{
    /**
     * Http code.
     *
     * @var int
     */
    protected $code;

    /**
     * Page html.
     *
     * @var string|null
     */
    protected $html;

    /**
     * Error.
     *
     * @var string|null
     */
    protected $error;

    /**
     * Page constructor.
     *
     * @param int $code
     * @param null|string $html
     * @param null|string $error
     */
    public function __construct(int $code, string $html = null, string $error = null)
    {
        $this->code = $code;
        $this->html = $html;
        $this->error = $error;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function getHtml(): ?string
    {
        return $this->html;
    }

    /**
     * {@inheritdoc}
     */
    public function getError(): ?string
    {
        return $this->error;
    }
}
