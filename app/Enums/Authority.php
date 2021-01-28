<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Authority extends Enum
{
    const ADMIN = 999;
    const READ_AND_WRITE = 500;
    const READ_ONLY = 100;
}
