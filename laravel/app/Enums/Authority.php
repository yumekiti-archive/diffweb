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
    const ADMIN = 0;
    const READ_AND_WRITE = 1;
    const READ_ONLY = 2;

}
