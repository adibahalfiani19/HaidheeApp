<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class PrayerTimeController extends Controller
{    
    public function getPrayerTimes(Request $request)
    {
        // Ambil lokasi pengguna berdasarkan IP
        $ipResponse = Http::get('http://ip-api.com/json/');
        if (!$ipResponse->successful()) {
            return response()->json(['error' => 'Gagal mendapatkan lokasi'], 500);
        }

        $location = $ipResponse->json();
        $latitude = $location['lat'] ?? null;
        $longitude = $location['lon'] ?? null;
        $region = $location['regionName'] . ', ' . $location['country'];

        if (!$latitude || !$longitude) {
            return response()->json(['error' => 'Gagal mendapatkan koordinat'], 500);
        }

        // Ambil waktu salat berdasarkan lokasi
        $prayerResponse = Http::get('https://api.aladhan.com/v1/timings', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'method' => 20 // KEMENAG Indonesia
        ]);

        if ($prayerResponse->successful()) {
            $prayerTimes = $prayerResponse->json()['data']['timings'];

            // Hitung Sholat Selanjutnya
            $currentTime = now()->format('H:i');
            $nextPrayer = $this->getNextPrayer($prayerTimes, $currentTime);

            return response()->json([
                'region' => $region,
                'prayerTimes' => $prayerTimes,
                'nextPrayer' => $nextPrayer
            ]);
        } else {
            return response()->json(['error' => 'Gagal mengambil waktu salat'], 500);
        }
    }

    private function getNextPrayer($prayerTimes, $currentTime)
    {
        $nextPrayer = null;
        $remainingTime = null;

        foreach ($prayerTimes as $name => $time) {
            if ($time > $currentTime) {
                $nextPrayer = $name;
                $remainingTime = $this->calculateRemainingTime($time);
                break;
            }
        }

        return [
            'name' => $nextPrayer ?? 'Subuh Besok',
            'remainingTime' => $remainingTime
        ];
    }

    private function calculateRemainingTime($targetTime)
    {
        $target = \Carbon\Carbon::createFromFormat('H:i', $targetTime);
        $now = now();

        return $target->diff($now)->format('%H:%I:%S');
    }
}
