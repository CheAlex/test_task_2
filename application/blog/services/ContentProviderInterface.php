<?php

namespace Blog\Service;

interface ContentProviderInterface
{
    public function get(string $host, string $uri): ContentResponse;
}
