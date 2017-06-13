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
    public $id;

    /**
     * Page html.
     *
     * @var string
     */
    public $html;

    /**
     * Host.
     *
     * @var string
     */
    public $host;

    /**
     * Uri.
     *
     * @var string
     */
    public $uri;
}
