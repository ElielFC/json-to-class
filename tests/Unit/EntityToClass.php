<?php

namespace ElielFC\ToClass\Test\Unit;

use ElielFC\ToClass\{
    Contract\ToClass,
    BaseToClass
};

class EntityToClass extends BaseToClass implements ToClass
{
    public int $id;
    public bool $active;
}
