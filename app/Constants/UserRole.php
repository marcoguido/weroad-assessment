<?php

namespace App\Constants;

use Datomatic\LaravelEnumHelper\LaravelEnumHelper;

enum UserRole: string
{
    use LaravelEnumHelper;

    case ADMIN = 'admin';
    case EDITOR = 'editor';
}
