<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OtpMail;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Show Login Form
    public function showLogin() {
        return view('auth.login');
    }

    // Handle Login
    public function login(Request $request) {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                
                if (!$user->is_verified) {
                    // Generate and send new OTP if not verified
                    try {
                        $this->sendOtp($user);
                    } catch (\Exception $e) {
                         Log::error('OTP Send Error (Login): ' . $e->getMessage());
                    }
                    return redirect()->route('verify.otp.view')->with('info', 'Please verify your email with the OTP sent.');
                }

                // Check if user has been inactive for 2 months
                if ($user->last_login_at && $user->last_login_at < now()->subMonths(2)) {
                    // User has been inactive for 2+ months, require OTP verification
                    try {
                        $this->sendOtp($user);
                        session(['requires_inactive_otp' => true, 'inactive_user_id' => $user->id]);
                        Auth::logout();
                        return redirect()->route('verify.otp.view')->with('info', 'For security reasons, please verify your identity with the OTP sent to your email.');
                    } catch (\Exception $e) {
                         Log::error('OTP Send Error (Inactive): ' . $e->getMessage());
                    }
                }

                // Update last login timestamp
                $user->last_login_at = now();
                $user->save();

                $request->session()->regenerate();
                
                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                
                return redirect()->route('home');
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        } catch (\Exception $e) {
            Log::error('Login Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to login. Please try again.');
        }
    }

    // Show Register Form
    public function showRegister() {
        return view('auth.register');
    }

    // Handle Register
    public function register(Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'customer'
            ]);

            try {
                $this->sendOtp($user);
            } catch (\Exception $e) {
                Log::error('OTP Send Error (Register): ' . $e->getMessage());
                // Allow registration but warn about email
                Auth::login($user);
                return redirect()->route('verify.otp.view')->with('warning', 'Account created but unable to send email. Please try resending OTP.');
            }

            Auth::login($user);

            return redirect()->route('verify.otp.view');
        } catch (\Exception $e) {
            Log::error('Registration Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to register. Please try again.');
        }
    }

    private function sendOtp($user) {
        $otp = rand(100000, 999999);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10)
        ]);

        Mail::to($user->email)->send(new OtpMail($otp));
    }

    public function showVerifyOtp() {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request) {
        try {
            $request->validate(['otp' => 'required|numeric']);
            
            // Check if this is an inactive user verification
            if (session('requires_inactive_otp') && session('inactive_user_id')) {
                $user = User::find(session('inactive_user_id'));
                
                if ($user && $user->otp == $request->otp && Carbon::now()->isBefore($user->otp_expires_at)) {
                    $user->update([
                        'otp' => null,
                        'otp_expires_at' => null,
                        'last_login_at' => now()
                    ]);
                    
                    // Clear session flags
                    session()->forget(['requires_inactive_otp', 'inactive_user_id']);
                    
                    // Log the user in
                    Auth::login($user);
                    $request->session()->regenerate();
                    
                    return $user->role === 'admin' 
                        ? redirect()->route('admin.dashboard')->with('success', 'Welcome back!') 
                        : redirect()->route('home')->with('success', 'Welcome back!');
                }
                
                return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
            }
            
            // Regular OTP verification for newly registered users
            $user = Auth::user();

            if ($user->otp == $request->otp && Carbon::now()->isBefore($user->otp_expires_at)) {
                $user->update([
                    'is_verified' => true,
                    'otp' => null,
                    'otp_expires_at' => null,
                    'email_verified_at' => now(),
                    'last_login_at' => now()
                ]);

                return redirect()->route('home')->with('success', 'Email verified successfully!');
            }

            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        } catch (\Exception $e) {
            Log::error('OTP Verification Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to verify OTP.');
        }
    }

    public function resendOtp() {
        try {
            // Check if this is an inactive user resend
            if (session('requires_inactive_otp') && session('inactive_user_id')) {
                $user = User::find(session('inactive_user_id'));
                
                if ($user) {
                    \Log::info('Resend OTP called for inactive user: ' . $user->email);
                    $this->sendOtp($user);
                    \Log::info('New OTP sent to inactive user: ' . $user->email);
                    return back()->with('success', 'A new OTP has been sent to your email.');
                }
            }
            
            // Regular resend for logged-in users
            $user = Auth::user();
            
            \Log::info('Resend OTP called for user: ' . $user->email);
            
            if ($user->is_verified) {
                return redirect()->route('home')->with('info', 'Your account is already verified.');
            }

            $this->sendOtp($user);
            
            \Log::info('New OTP sent to: ' . $user->email);

            return back()->with('success', 'A new OTP has been sent to your email.');
        } catch (\Exception $e) {
            Log::error('Resend OTP Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to resend OTP.');
        }
    }

    // Handle Logout
    public function logout(Request $request) {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login');
        } catch (\Exception $e) {
            Log::error('Logout Error: ' . $e->getMessage());
            return redirect()->route('login');
        }
    }
}
