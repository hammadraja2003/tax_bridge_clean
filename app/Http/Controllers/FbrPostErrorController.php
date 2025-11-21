<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FbrPostError;

class FbrPostErrorController extends Controller
{
    /**
     * Display a paginated list of FBR post errors.
     *
     * @return \Illuminate\View\View
     */
    public function showErrors()
    {
        $fbr_errors = FbrPostError::orderByDesc('id')->paginate(10);
        if (isApiRequest()) {
            return paginatedResponse($fbr_errors, 'FBR Errors Data Fetched');
        }
        return view('invoices.fbr_post_error', compact('fbr_errors'));
    }
}