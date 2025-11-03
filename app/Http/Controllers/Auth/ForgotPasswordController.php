<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.new-password');
    }
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink(
            $request->only('email')
        );
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'A password reset link has been sent to your email. Please check your inbox.');
        } else {
            return back()->withErrors(['email' => __($status)]);
        }
    }
}
