<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class PrayerTimeController extends Controller
{    
    public function getPrayerTimes(Request $request)
    {
        // Ambil alamat IP pengguna
        $userIp = $request->ip();

        // Periksa apakah alamat IP berhasil diperoleh
        if (!$userIp) {
            // Jika tidak, gunakan koordinat default untuk Jakarta
            $latitude = -6.2088;
            $longitude = 106.8456;
            $region = 'Jakarta, Indonesia';
        } else {
            // Ambil lokasi pengguna berdasarkan alamat IP
            $ipResponse = Http::get("http://ip-api.com/json/{$userIp}");
            if ($ipResponse->successful()) {
                $location = $ipResponse->json();
                $latitude = $location['lat'] ?? -6.2088;
                $longitude = $location['lon'] ?? 106.8456;
                $region = ($location['regionName'] ?? 'Jakarta') . ', ' . ($location['country'] ?? 'Indonesia');
            } else {
                // Jika permintaan ke IP-API gagal, gunakan koordinat default untuk Jakarta
                $latitude = -6.2088;
                $longitude = 106.8456;
                $region = 'Jakarta, Indonesia';
            }
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
            'name' => $nextPrayer,
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
