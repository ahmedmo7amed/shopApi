<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
	// Inherit middleware(), authorize(), and other helpers from Laravel's base controller
}
