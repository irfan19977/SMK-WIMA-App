<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function index()
    {
        return view('frontend.qrcode.index', [
            'formats' => [
                'png' => 'PNG',
                'svg' => 'SVG',
                'eps' => 'EPS'
            ]
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'size' => 'sometimes|integer|min:100|max:1000',
            'format' => 'required|in:png,svg,eps'
        ]);

        $url = $request->input('url');
        $size = $request->input('size', 300);
        $format = $request->input('format', 'png');

        // Normalisasi URL Google Form
        if (str_contains($url, 'forms.gle/') || str_contains($url, 'docs.google.com/forms')) {
            // Jika URL sudah dalam format forms.gle, biarkan seperti itu
            if (str_starts_with($url, 'https://forms.gle/')) {
                $shortUrl = $url;
                // Coba dapatkan URL lengkap untuk preview
                try {
                    $response = Http::head($url);
                    if ($response->successful()) {
                        $url = $response->effectiveUri();
                    }
                } catch (\Exception $e) {
                    // Tetap gunakan URL asli jika tidak bisa mendapatkan URL lengkap
                }
            } 
            // Jika URL dalam format docs.google.com, konversi ke forms.gle
            elseif (str_contains($url, 'docs.google.com/forms')) {
                // Simpan URL asli untuk ditampilkan
                $originalUrl = $url;
                
                // Coba dapatkan URL pendek
                try {
                    $response = Http::get($url);
                    if ($response->successful()) {
                        // Cari URL pendek di meta tag
                        if (preg_match('/<meta[^>]+content="(https?:\/\/forms\.gle\/[^"]+)"/', $response->body(), $matches)) {
                            $shortUrl = $matches[1];
                            $url = $shortUrl;
                        }
                    }
                } catch (\Exception $e) {
                    // Tetap gunakan URL asli jika tidak bisa mendapatkan URL pendek
                }
            }
        }

        // Pastikan margin dan error correction yang tepat
        $qrcode = QrCode::format($format)
            ->size($size)
            ->margin(1)  // Margin minimal untuk memastikan bisa discan
            ->errorCorrection('H')  // Error correction level High
            ->generate($url);

        if ($format === 'svg') {
            $mimeType = 'image/svg+xml';
            $extension = 'svg';
            $base64 = 'data:image/svg+xml;base64,' . base64_encode($qrcode);
            $rawSvg = $qrcode;  // Simpan SVG mentah untuk ditampilkan langsung
        } elseif ($format === 'eps') {
            $mimeType = 'application/postscript';
            $extension = 'eps';
            $base64 = 'data:application/postscript;base64,' . base64_encode($qrcode);
            $rawSvg = null;
        } else {
            $mimeType = 'image/png';
            $extension = 'png';
            $base64 = 'data:image/png;base64,' . base64_encode($qrcode);
            $rawSvg = null;
        }

        return view('frontend.qrcode.result', [
            'qrcode' => 'data:image/' . $format . ';base64,' . base64_encode($qrcode),
            'url' => $url,
            'shortUrl' => $shortUrl ?? $url,
            'size' => $size,
            'format' => $format,
            'isGoogleForm' => str_contains($url, 'forms.gle/') || str_contains($url, 'docs.google.com/forms'),
            'extension' => $extension,
            'rawSvg' => $rawSvg ?? null
        ]);
    }
}
