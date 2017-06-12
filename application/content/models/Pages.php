<?php

namespace Store\Toys;

use Phalcon\Mvc\Model;

class Pages extends Model
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

//    public function getSource()
//    {
//        return 'page';
//    }
}
