<?php

namespace BlogModule\Service\ContentProvider;

interface ContentInterface
{
    const CODE_OK = 200;

    /**
     * @return int
     */
    public function getCode(): int;

    /**
     * @return null|string
     */
    public function getContent(): ?string;

    /**
     * @return null|string
     */
    public function getError(): ?string;
}
