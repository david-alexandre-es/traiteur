<?php

namespace App\Enum;

enum RoleEnum: string
{
    case UTILISATEUR = 'ROLE_USER';
    case EMPLOYE = 'ROLE_EMPLOYE';
    case ADMINISTRATEUR = 'ROLE_ADMIN';
}