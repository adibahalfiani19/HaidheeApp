<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qada Salat Notification</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h2 style="color: #5F7E78;">Assalamualaikum, Kak {{ $userName }}!</h2>

    <p>Terima kasih sudah menggunakan <strong>Haidhee</strong> untuk mencatat menstruasimu.</p>

    <p>Untuk menstruasimu yang dimulai pada <strong>{{ $startDate }}</strong> dan selesai pada <strong>{{ $endDate }}</strong>, masih ada salat yang belum dikerjakan, yaitu:</p>
    <ul>
        @foreach ($qadaSalat as $salat)
            <li>{{ $salat }}</li>
        @endforeach
    </ul>

    <p>Jangan lupa untuk mengqada salatnya ya, Kak. Jika sudah diqada, pastikan untuk melakukan checklist atau pencatatan ulang di aplikasi <strong>Haidhee</strong>.</p>

    <p><a href="{{ $appLink }}" style="color: #D4BE83; text-decoration: none; font-weight: bold;">Klik di sini untuk membuka aplikasi Haidhee</a></p>

    <p>Salam hangat,<br>Admin <strong>Haidhee</strong></p>
</body>
</html>
