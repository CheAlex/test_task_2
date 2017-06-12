<?php

namespace Che\ContentModule\Entity;

use Phalcon\Mvc\Model;

class Page extends Model
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $host;

    /**
     * @var string
     */
    public $uri;
}
