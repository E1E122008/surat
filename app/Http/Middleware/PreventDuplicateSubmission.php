<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Mencegah form yang sama tersimpan dua kali (klik ganda / koneksi lambat).
 *
 * Setiap form POST diberi token unik (_submit_token) oleh layout. Kiriman pertama
 * diproses normal; kiriman berikutnya dengan token yang sama tidak diproses ulang,
 * melainkan diarahkan ke hasil kiriman pertama.
 */
class PreventDuplicateSubmission
{
    private const TTL = 600; // detik

    public function handle(Request $request, Closure $next)
    {
        $token = $request->input('_submit_token');

        if ($request->isMethod('GET') || !is_string($token) || $token === '' || strlen($token) > 100) {
            return $next($request);
        }

        $key = 'submit_token:' . ($request->user()?->id ?? $request->ip()) . ':' . $token;

        // Cache::add atomik: hanya kiriman pertama yang berhasil menandai token
        if (!Cache::add($key, ['status' => 'pending'], self::TTL)) {
            return $this->replayFirstResult($key);
        }

        $response = $next($request);

        if ($response instanceof RedirectResponse) {
            Cache::put($key, [
                'status' => 'done',
                'url' => $response->getTargetUrl(),
                'success' => session('success'),
                'error' => session('error'),
            ], self::TTL);
        } else {
            // Bukan redirect (mis. unduhan/halaman langsung): izinkan kirim ulang
            Cache::forget($key);
        }

        return $response;
    }

    private function replayFirstResult(string $key)
    {
        // Tunggu kiriman pertama selesai (maks. ~15 detik)
        for ($i = 0; $i < 75; $i++) {
            $result = Cache::get($key);
            if (!$result || $result['status'] === 'done') {
                break;
            }
            usleep(200000);
        }

        if (!empty($result['url'])) {
            $redirect = redirect()->to($result['url']);
            if (!empty($result['success'])) {
                $redirect->with('success', $result['success']);
            }
            if (!empty($result['error'])) {
                $redirect->with('error', $result['error']);
            }

            return $redirect;
        }

        return redirect()->back()
            ->with('error', 'Form ini sedang diproses. Mohon tunggu dan periksa daftar data sebelum mengirim ulang.');
    }
}
