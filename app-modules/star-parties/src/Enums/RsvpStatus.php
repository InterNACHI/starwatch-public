<?php

namespace StarWatch\StarParties\Enums;

use App\Support\EnumHelpers;

enum RsvpStatus: string
{
	use EnumHelpers;
	
	case Confirmed = 'confirmed';
}
