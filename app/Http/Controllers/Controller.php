<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
    
    /**
		* @OA\Info(
		*    title="Your super  ApplicationAPI",
		*    version="1.0.0",
		* )
		* @OA\SecurityScheme(
		*        type="http",
		*        description="Login with email and password to get the authentication token",
		*       name="Token based Based",
		*        in="header",
		*        scheme="bearer",
		*        bearerFormat="JWT",
		*        securityScheme="apiAuth",
		* ),
	*/
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
