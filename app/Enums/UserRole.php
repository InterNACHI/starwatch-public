<?php

namespace App\Enums;

use App\Support\EnumHelpers;

enum UserRole: string
{
	use EnumHelpers;
	
	case Member = 'member';
	case Organizer = 'organizer';
	case Admin = 'admin';
}
