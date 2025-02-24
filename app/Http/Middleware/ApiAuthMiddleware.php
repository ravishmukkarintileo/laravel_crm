<?php

namespace App\Http\Middleware;

use Closure;
use Examyou\RestAPI\Exceptions\ApiException;
use Illuminate\Support\Facades\Redirect;
use App\Models\ip_whitelist;

class ApiAuthMiddleware
{

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

        $userIp = $request->ip();

        // Get all whitelisted IPs
        $ipAddresses = ip_whitelist::pluck('ip')->toArray();

        // Check if user IP exists in the whitelist

		if (!auth('api')->check()) {
            throw new ApiException('UNAUTHORIZED EXCEPTION', null, 401, 401);
		}
        if (auth('api')->user()->role_id != 1 && !in_array($userIp, $ipAddresses)) {
            throw new ApiException('UNAUTHORIZED IP', null, 401, 401);
        }

		return $next($request);
	}
}
