<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Cek status user
            if ($user->status === 'inactive') {
                Log::info('Inactive user logged out automatically', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'ip' => $request->ip()
                ]);

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.');
            }

            // Cek relasi dengan penduduk untuk user masyarakat
            if ($user->role === 'masyarakat') {
                try {
                    // Eagerly load penduduk to ensure relationship is available
                    $penduduk = $user->penduduk;
                    
                    if (!$penduduk) {
                        Log::warning('Masyarakat user without penduduk data', [
                            'user_id' => $user->id,
                            'user_email' => $user->email,
                            'ip' => $request->ip()
                        ]);
                        // Don't logout, just continue - let the API controller handle this
                    } elseif ($penduduk->status === 'inactive') {
                        Log::info('User with inactive penduduk logged out automatically', [
                            'user_id' => $user->id,
                            'user_email' => $user->email,
                            'penduduk_id' => $penduduk->id,
                            'penduduk_nik' => $penduduk->nik,
                            'ip' => $request->ip()
                        ]);

                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();

                        return redirect()->route('login')->with('error', 'Data kependudukan Anda telah dinonaktifkan. Silakan hubungi administrator.');
                    }
                } catch (\Exception $e) {
                    Log::error('Error checking penduduk relationship', [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Don't logout on error, let request continue
                }
            }
        }

        return $next($request);
    }
}
