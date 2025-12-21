<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        if ($this->isMobile($request->header('User-Agent'))) {
            return view('company.index_sp');
        }
        return view('company.index');
    }

    public function terms(Request $request)
    {
        if ($this->isMobile($request->header('User-Agent'))) {
            return view('company.terms_sp');
        }
        return view('company.terms');
    }

    public function policy(Request $request)
    {
        if ($this->isMobile($request->header('User-Agent'))) {
            return view('company.policy_sp');
        }
        return view('company.policy');
    }

    private function isMobile($userAgent)
    {
        return preg_match('/(iPhone|iPod|Android.*Mobile)/i', $userAgent);
    }
}
