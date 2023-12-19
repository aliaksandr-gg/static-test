<?php

namespace App\Services;

use App\Contracts\TestInterface;

class TestIncorrectService implements TestInterface
{
    public const B = 'b';
    public int $test = 3;
}
