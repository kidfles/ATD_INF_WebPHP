<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function show(CompanyProfile $company)
    {
        return view('pages.whitelabel.show', compact('company'));
    }
}
