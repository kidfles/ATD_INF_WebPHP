<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UserRole;
use App\Enums\ContractStatus;

class EnsureContractApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user is authenticated
        if (!$user) {
            abort(403, 'Authenticatie vereist.');
        }

        // 1. If not a business user, deny access
        if ($user->role !== UserRole::BusinessSeller || !$user->companyProfile) {
            abort(403, 'Alleen voor zakelijke accounts.');
        }

        // 2. Check contract status
        // We assume a 'contract_status' column exists (pending, approved, rejected)
        if ($user->companyProfile->contract_status !== ContractStatus::Approved) {
            
            // If it's an API request, return JSON
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Contract nog niet goedgekeurd.'], 403);
            }

            // Otherwise redirect to settings page with error message
            return redirect()->route('dashboard.company.settings.edit')
                ->with('error', 'Deze functie is pas beschikbaar nadat uw contract is goedgekeurd.');
        }

        return $next($request);
    }
}
