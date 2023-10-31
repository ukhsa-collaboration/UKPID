<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum ForcedPasswordChangeReasons
{
    use EnumToArray;

    // Add public facing labels for reasons to lang/<lang>/forced-password-change.php
    case NEW_ACCOUNT;

    case ADMIN_OR_MANAGER_RESET;
}
