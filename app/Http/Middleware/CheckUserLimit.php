<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserLimit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->company) {
            $company = $user->company;
            $userLimit = $company->userLimit;

            if ($userLimit) {
                $userLimitValue = $userLimit->user_limit;
                $currentUserCount = $company->users()->count();

                if ($currentUserCount >= $userLimitValue) {
                    return redirect()->back()->with('error', 'User limit reached. Please upgrade your package to add more users.');
                }
            }
        }

        return $next($request);
    }
}
