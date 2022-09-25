<?php

declare(strict_types=1);

namespace ElielFC\ToClass\Contract;

interface ToClass
{
    public function make(string|array $json);
}
