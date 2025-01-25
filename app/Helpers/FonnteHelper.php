<?php

namespace App\Helpers;

class FonnteHelper
{
    public static function sendWhatsAppMessage($target, $message)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Kode negara Indonesia
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: YL3BirAi2tigkKo7c2X5', // Ganti dengan token API Anda
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}
