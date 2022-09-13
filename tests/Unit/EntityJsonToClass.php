<?php

namespace ElielFC\JsonToClass\Test\Unit;

use ElielFC\JsonToClass\{
    Contracts\JsonToClass,
    BaseJsonToClass
};

class EntityJsonToClass extends BaseJsonToClass implements JsonToClass
{
    public int $id;
    public bool $active;
}
