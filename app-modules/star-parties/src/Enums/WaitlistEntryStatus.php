<?php

namespace StarWatch\StarParties\Enums;

use App\Support\EnumHelpers;

enum WaitlistEntryStatus: string
{
    use EnumHelpers;

    case Waiting = 'waiting';
    case Promoted = 'promoted';
    case Cancelled = 'cancelled';
}
