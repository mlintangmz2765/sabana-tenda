<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $credentials['username'])
            ->orWhere('email', $credentials['username'])
            ->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'username' => 'Username atau email tidak ditemukan.',
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'username' => 'Akun Anda telah dinonaktifkan.',
            ]);
        }

        if ($user->isLocked()) {
            $minutes = (int) now()->diffInMinutes($user->locked_until, false);
            throw ValidationException::withMessages([
                'username' => "Too many failed login attempts. Coba lagi dalam {$minutes} menit.",
            ]);
        }

        if (! Hash::check($credentials['password'], $user->password)) {
            $user->increment('failed_login_attempts');

            $maxAttempts = (int) config('sabana.max_login_attempts');
            if ($user->failed_login_attempts >= $maxAttempts) {
                $user->update([
                    'locked_until' => now()->addMinutes((int) config('sabana.lockout_minutes')),
                    'failed_login_attempts' => 0,
                ]);
                throw ValidationException::withMessages([
                    'username' => 'Too many failed login attempts. Akun terkunci sementara.',
                ]);
            }

            throw ValidationException::withMessages([
                'password' => 'Password salah. Sisa percobaan: ' . ($maxAttempts - $user->failed_login_attempts),
            ]);
        }

        $user->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => now(),
        ]);

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended($this->redirectByRole($user))
            ->with('success', "Selamat datang, {$user->name}!");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'username' => ['required', 'string', 'min:4', 'max:60', 'unique:users,username', 'alpha_dash'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'],
        ], [
            'password.regex' => 'Password harus kombinasi huruf dan angka.',
        ]);

        $nextCode = sprintf('USR-%03d', (User::max('id') ?? 0) + 1);

        $user = User::create([
            'user_code' => $nextCode,
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_CUSTOMER,
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard')->with('success', 'Akun berhasil dibuat. Selamat datang!');
    }

    protected function redirectByRole(User $user): string
    {
        return match ($user->role) {
            User::ROLE_OWNER, User::ROLE_ADMIN => route('admin.dashboard'),
            User::ROLE_STAFF => route('staff.dashboard'),
            User::ROLE_CUSTOMER => route('customer.dashboard'),
            default => route('home'),
        };
    }
}
