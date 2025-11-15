<?php

namespace App\Enums;

enum InputType: int
{
    case TEXT = 0;
    case TEXTAREA = 1;
    case YESNO = 2;
    case SELECT = 3;
    case MULTISELECT = 4;
}
