<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Branch;

class CheckAnyBranchOpen
{
    public function handle(Request $request, Closure $next)
    {
        $hasOpenBranch = Branch::where('is_active', true)
            ->whereNull('deleted_at')
            ->exists();

        if (!$hasOpenBranch) {
            return redirect()->route('pods.closed');
        }

        return $next($request);
    }
}
