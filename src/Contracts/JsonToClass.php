<?php

declare(strict_types=1);

namespace ElielFC\JsonToClass\Contracts;

interface JsonToClass
{
    public function make(string|array $json);
}
