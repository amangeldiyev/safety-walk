<?php

namespace App\Enums;

enum AuditStatus: int
{
    case DRAFT = 0;
    case FINISHED = 1;
}
