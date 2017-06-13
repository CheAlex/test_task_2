<?php

namespace Che\BlogModule\Model;

/**
 * Interface PageInterface
 *
 * @package Che\BlogModule\Model
 */
interface PageInterface
{
    const CODE_OK = 200;

    /**
     * Get http code.
     *
     * @return int
     */
    public function getCode(): int;

    /**
     * Get page html.
     *
     * @return null|string
     */
    public function getHtml(): ?string;

    /**
     * Get error.
     *
     * @return null|string
     */
    public function getError(): ?string;
}
