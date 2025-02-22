<?php

namespace App\Enums;

enum AuditMode: int
{
    case CONVERSATION = 1;
    case GUIDED = 0;
}
