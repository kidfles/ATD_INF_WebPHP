<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function show(CompanyProfile $company)
    {
        $company->load(['user.advertisements' => function ($query) {
            $query->latest()->limit(3); // Fix N+1 and limit ads
        }, 'pageComponents' => function ($query) {
            $query->orderBy('order');
        }]);
        
        return view('pages.whitelabel.show', compact('company'));
    }
}
