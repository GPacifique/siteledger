<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * Redirect user to their role-specific dashboard
     */
    protected function redirectToRoleDashboard(User $user)
    {
        if ($user->is_super_admin) {
            return redirect()->route('super-admin.dashboard');
        }
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->hasRole('accountant')) {
            return redirect()->route('accountant.dashboard');
        }
        if ($user->hasRole('manager')) {
            return redirect()->route('manager.dashboard');
        }
        return redirect()->route('user.dashboard');
    }
    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return $this->redirectToRoleDashboard(Auth::user());
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle register request
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'current_tenant_id' => 1, // Assign to default tenant
        ]);

        // Ensure default role exists and assign it
        $defaultRole = Role::firstOrCreate(['name' => 'user']);
        $user->assignRole($defaultRole);

        // Attach user to default tenant with role
        try {
            $user->addToTenant(1, 'user', false);
        } catch (\Throwable $e) {
            // If tenant 1 doesn't exist, ignore silently
        }

        Auth::login($user);

        return $this->redirectToRoleDashboard($user);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
