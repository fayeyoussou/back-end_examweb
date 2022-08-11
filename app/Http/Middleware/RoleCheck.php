<?php

namespace App\Http\Middleware;

use App\Http\Resources\UserResource;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$role)
    {
        $id=  auth('sanctum')->user()->getAuthIdentifier();
        // return response()->json($id);
        $user = User::find($id);
        $find = false;
        foreach ($user->roles()->get() as $rolelist) {
            if($rolelist->nom == $role) $find = true;
        }
        if($find) return $next($request);
        else return response()->json('Unauthorized',401);
    }
}
