<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response;

class CheckTodoOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $todo = $request->route('todo');

        if (!$todo || $todo->user_id !== request()->user()->id) {
            return FacadesResponse::api('You are not authorized to perform this action', null, 403);
        }

        return $next($request);
    }
}
