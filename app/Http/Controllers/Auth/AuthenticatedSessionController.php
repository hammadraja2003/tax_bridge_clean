<?php

// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use App\Http\Requests\Auth\LoginRequest;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\View\View;

// class AuthenticatedSessionController extends Controller
// {
//     public function create(): View|RedirectResponse
//     {
//         if (Auth::check()) {
//             return redirect()->route('dashboard');
//         }
//         return view('auth.login');
//     }

//     public function store(Request $request)
//     {
//         $credentials = $request->only('email', 'password');

//         if (Auth::guard('web')->attempt($credentials)) {
//             $user = Auth::guard('web')->user();
//             if ($user->twofa_enabled) {
//                 Auth::logout();
//                 $request->session()->invalidate();
//                 $request->session()->regenerateToken();
//                 $request->session()->put('2fa:user:id', $user->id);
//                 return redirect()->route('2fa.verify');
//             }

//             $request->session()->regenerate();

//             $tenantId = $user->tenant_id ?? null;
//             if ($tenantId) {
//                 app(\App\Services\TenantManager::class)->setTenant($tenantId);
//                 $request->session()->put('tenant_id', $tenantId); // persist for middleware
//             }

//             $intended = redirect()->intended()->getTargetUrl();
//             if ($intended === url('login') || $intended === url('/login')) {
//                 $intended = route('dashboard');
//             }
//             return redirect($intended)->with('message', 'User Login Successful');
//         }
//         return redirect()->route('login')
//             ->withErrors([
//                 'db' => "Invalid credentials."
//             ]);
//     }

//     public function destroy(Request $request): RedirectResponse
//     {
//         Auth::guard('web')->logout();
//         $request->session()->invalidate();
//         $request->session()->regenerateToken();
//         return redirect('/')->with('success', 'Logged out.');
//     }
// }


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(): View|RedirectResponse
    {
        // Prevent showing login page if already logged in
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate input (helps avoid CSRF-like empty posts)
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('web')->user();

            // --- Handle 2FA ---
            if ($user->twofa_enabled) {
                Auth::logout();
                
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                $request->session()->put('2fa:user:id', $user->id);
                return redirect()->route('2fa.verify');
            }

            // --- Normal login ---
            $request->session()->regenerate();

            // Tenant context (optional multi-tenant)
            if (!empty($user->tenant_id)) {
                app(\App\Services\TenantManager::class)->setTenant($user->tenant_id);
                $request->session()->put('tenant_id', $user->tenant_id);
            }

            // --- Safe redirect (avoid looping to login) ---
            $intended = redirect()->intended(route('dashboard'))->getTargetUrl();

            if (in_array($intended, [url('/login'), url('login'), url('/')])) {
                $intended = route('dashboard');
            }

            return redirect($intended)->with('message', 'User Login Successful');
        }

        // Invalid credentials
        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    /**
     * Logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
