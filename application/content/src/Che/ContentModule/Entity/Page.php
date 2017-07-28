<?php

namespace Che\ContentModule\Entity;

use Phalcon\Mvc\Model;

/**
 * Class Page.
 *
 * @package Che\ContentModule\Entity
 */
class Page extends Model
{
    /**
     * Id.
     *
     * @var int
     */
    protected $id;

    /**
     * Page html.
     *
     * @var string
     */
    protected $html;

    /**
     * Host.
     *
     * @var string
     */
    protected $host;

    /**
     * Uri.
     *
     * @var string
     */
    protected $uri;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return $this->html;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}
