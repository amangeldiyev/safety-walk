<?php

namespace App\Enums;

enum AuditMode: int
{
    case VEHICLE_SAFETY = 2;
    case CONVERSATION = 1;
    case GUIDED = 0;
}
