<?php

namespace App\Enums;

enum RoleEnum: string
{
    case Customer = 'Customer';
    case Company = 'Company';
    case Admin = 'Admin';
}
