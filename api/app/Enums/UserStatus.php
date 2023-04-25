<?php


namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'Active';
    case PASSIVE = 'Passive';
    case FORBIDDEN = 'Forbidden';
}
