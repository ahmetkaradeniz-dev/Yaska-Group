<?php


namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'Active';
    case FORBIDDEN = 'Forbidden';
}
