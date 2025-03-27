<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menstruation;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use App\Mail\QadaNotification;
use App\Mail\QadaNotificationMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Helpers\FonnteHelper;

class MenstruationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'start_time' => 'required',
            'prayer_start' => 'nullable|string',
            'end_date' => 'nullable|date',
            'end_time' => 'nullable',
            'prayer_end' => 'nullable|string',
        ]);

        $status = $this->determineStatus($request->start_date, $request->start_time, $request->end_date, $request->end_time);

        if ($request->has('id') && $request->id) {
            // Update data berdasarkan ID
            $menstruation = Menstruation::findOrFail($request->id);
            $menstruation->update([
                'start_date' => $request->start_date,
                'start_time' => $request->start_time,
                'prayer_start' => $request->prayer_start,
                'end_date' => $request->end_date,
                'end_time' => $request->end_time,
                'prayer_end' => $request->prayer_end,
                'status' => $status, 
            ]);
            return response()->json(['message' => 'Data berhasil diperbarui'], 200);
        } else {
            // Simpan data baru
            Menstruation::create([
                'user_id' => Auth::id(),
                'start_date' => $request->start_date,
                'start_time' => $request->start_time,
                'prayer_start' => $request->prayer_start,
                'end_date' => $request->end_date,
                'end_time' => $request->end_time,
                'prayer_end' => $request->prayer_end,
                'status' => $status, 
            ]);
            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        }
    }

    public function home()
    {
        $userId = Auth::id();

        // Cek apakah ada prayer_start atau prayer_end yang tidak null
        $hasPendingPrayers = Menstruation::where('user_id', $userId)
                            ->where(function ($query) {
                                $query->whereNotNull('prayer_start')
                                    ->orWhereNotNull('prayer_end');
                            })
                            ->exists();

        $latestArticles = Article::orderBy('published_date', 'desc')->take(3)->get(); // Ambil 3 artikel terbaru
        return view('user.home', compact('latestArticles', 'hasPendingPrayers'));
    }

    public function showHistory()
    {
        $userId = Auth::id();
        
        // Urutkan berdasarkan start_date yang paling terbaru
        $riwayat = Menstruation::where('user_id', $userId)
            ->orderBy('start_date', 'desc') // Mengurutkan berdasarkan tanggal mulai (paling baru di atas)
            ->paginate(5); // Tampilkan 5 data per halaman
    
        // Hitung durasi haid dan istihadhah untuk setiap item
        foreach ($riwayat as $item) {
            // Format tanggal start_date dan end_date
            $item->start_date = Carbon::parse($item->start_date)->format('d-m-Y');
            $item->end_date = $item->end_date ? Carbon::parse($item->end_date)->format('d-m-Y') : '-';
            
            $durasi = $this->calculateDurations($item);
            $item->durasi_haid = $durasi['durasi_haid'];
            $item->durasi_istihadhah = $durasi['durasi_istihadhah'];
        }

        // Rata-rata durasi haid (dibulatkan menjadi nilai bulat)
        $averageHaid = Menstruation::where('user_id', $userId)
            ->whereNotNull('end_date')
            ->selectRaw('AVG(DATEDIFF(end_date, start_date) + 1) as avg_duration')
            ->value('avg_duration');
        $averageHaid = $averageHaid ? round($averageHaid) : 0;
    
        // Rata-rata masa suci (dibulatkan menjadi nilai bulat)
        $averageClean = Menstruation::where('user_id', $userId)
            ->whereNotNull('end_date')
            ->whereRaw('(SELECT MAX(start_date) FROM menstruations m2 WHERE m2.user_id = menstruations.user_id AND m2.start_date < menstruations.start_date) IS NOT NULL')
            ->selectRaw('AVG(DATEDIFF(start_date, (SELECT MAX(end_date) FROM menstruations m2 WHERE m2.user_id = menstruations.user_id AND m2.start_date < menstruations.start_date))) as avg_clean')
            ->value('avg_clean');
        $averageClean = $averageClean ? round($averageClean) : 0;
    
        // Rata-rata siklus (dibulatkan menjadi nilai bulat)
        $averageCycle = Menstruation::where('user_id', $userId)
            ->whereRaw('(SELECT MAX(start_date) FROM menstruations m2 WHERE m2.user_id = menstruations.user_id AND m2.start_date < menstruations.start_date) IS NOT NULL')
            ->selectRaw('AVG(DATEDIFF(start_date, (SELECT MAX(start_date) FROM menstruations m2 WHERE m2.user_id = menstruations.user_id AND m2.start_date < menstruations.start_date))) as avg_cycle')
            ->value('avg_cycle');
        $averageCycle = $averageCycle ? round($averageCycle) : 0;
    
        return view('user.riwayat', compact('riwayat', 'averageHaid', 'averageClean', 'averageCycle'));
    }

    public function create()
    {
        return view('user.calendar.create', [
            'menstruation' => null,
            'activeForm' => 'start', // Default form aktif adalah start
        ]);
    }

    public function edit($id)
    {
        $menstruation = Menstruation::findOrFail($id);
        return view('user.calendar.edit', [
            'menstruation' => $menstruation,
            'activeForm' => 'start', // Default adalah form start
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'start_date' => 'required|date',
            'start_time' => 'required',
            'prayer_start' => 'nullable|string',
            'end_date' => 'nullable|date',
            'end_time' => 'nullable',
            'prayer_end' => 'nullable|string',
        ]);
        
        $status = $this->determineStatus($request->start_date, $request->start_time, $request->end_date, $request->end_time);

        // Update data berdasarkan ID
        $menstruation = Menstruation::findOrFail($id);
        $menstruation->update([
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'prayer_start' => $request->prayer_start,
            'end_date' => $request->end_date,
            'end_time' => $request->end_time,
            'prayer_end' => $request->prayer_end,
            'status' => $status, // Update status
        ]);

        // Cek jika ada salat yang perlu diqada
        $qadaSalat = [];
        if (!empty($menstruation->prayer_start)) {
            $qadaSalat[] = ucfirst($menstruation->prayer_start);
        }
        if (!empty($menstruation->prayer_end)) {
            $qadaSalat[] = ucfirst($menstruation->prayer_end);
        }

        if (!empty($qadaSalat)) {
            $user = $menstruation->user;
            // Format pesan
            $message = "Assalamualaikum, Kak {$user->name}!\n\n" .
                    "Terima kasih sudah menggunakan *Haidhee* untuk mencatat menstruasimu.\n\n" .
                    "Masih ada salat yang belum dikerjakan, yaitu:\n";

            // Tambahkan daftar salat dengan format langsung di dalam pesan
            foreach ($qadaSalat as $salat) {
                $message .= "• *Salat {$salat}*\n";
            }

            $message .= "\nJangan lupa untuk mengqada salatnya ya, Kak. Jika sudah diqada, pastikan untuk melakukan checklist atau pencatatan ulang di aplikasi Haidhee.\n" .
                        "Buka aplikasi Haidhee di sini: \nhttps://haidhee.com\n\n" .
                        "Salam hangat,\nAdmin Haidhee :)";
    
            if ($user->whatsapp_number) {
                FonnteHelper::sendWhatsAppMessage($user->whatsapp_number, $message);
            } else {
                Mail::to($user->email)->send(new QadaNotificationMail(
                    $user->name,
                    $menstruation->start_date,
                    $menstruation->end_date,
                    $qadaSalat
                ));
            }
        }
    
        // // Kirim email jika ada salat yang perlu diqada
        // if (!empty($qadaSalat)) {
        //     $user = $menstruation->user; // Pastikan relasi user ada
        //     Mail::to($user->email)->send(new QadaNotificationMail(
        //         $user->name,
        //         $menstruation->start_date,
        //         $menstruation->end_date,
        //         $qadaSalat
        //     ));
        // }

        return response()->json(['message' => 'Data berhasil diperbarui'], 200);
    }   

    public function destroy($id)
    {
        // Cari data berdasarkan ID
        $data = Menstruation::findOrFail($id);
        
        // Hapus data
        $data->delete();
        
        // Redirect kembali ke halaman dengan pesan sukses
        return redirect()->route('riwayat.menstruasi')->with('success', 'Data berhasil dihapus.');
    }

    private function determineStatus($startDate, $startTime, $endDate = null, $endTime = null)
    {
        $startDateTime = new \DateTime("$startDate $startTime");
    
        // Ambil haid terakhir dari user
        $lastMenstruation = Menstruation::where('user_id', Auth::id())
            ->whereNotNull('end_date')
            ->orderBy('end_date', 'desc')
            ->first();
    
        $status = 'Belum Selesai';
        $lastCycleEnd = null;
    
        if ($lastMenstruation) {
            $lastEndDateTime = new \DateTime($lastMenstruation->end_date . ' ' . $lastMenstruation->end_time);
            $lastStartDateTime = new \DateTime($lastMenstruation->start_date . ' ' . $lastMenstruation->start_time);
    
            // Hitung batas maksimal siklus sebelumnya (15 hari dari start siklus sebelumnya)
            $lastCycleEnd = clone $lastStartDateTime;
            $lastCycleEnd->modify('+15 days');
    
            // Validasi jika endDateTime masih dalam rentang siklus sebelumnya
            if ($endDate && $endTime) {
                $endDateTime = new \DateTime("$endDate $endTime");
    
                if ($endDateTime <= $lastCycleEnd) {
                    $status = 'Haid';
                    return $status; 
                }
            }
    
            // Hitung interval antara haid terakhir selesai dan haid baru mulai (dalam jam)
            $intervalInHours = ($lastEndDateTime->diff($startDateTime)->days * 24) + $lastEndDateTime->diff($startDateTime)->h;
    
            // Jika darah keluar sebelum masa suci 15 hari penuh
            if ($intervalInHours < (15 * 24)) {
                if ($startDateTime <= $lastCycleEnd) {
                    $status = 'Haid';
                } else {
                    $status = 'Istihadhah';
                }
            } else {
                $status = 'Haid';
            }
        } else {
            $status = 'Haid';
        }
    
        // Jika ada end_date dan end_time, hitung ulang status
        if ($endDate && $endTime) {
            $endDateTime = new \DateTime("$endDate $endTime");
            $durationInHours = ($startDateTime->diff($endDateTime)->days * 24) + $startDateTime->diff($endDateTime)->h;
    
            // Tambahan pengecekan jika total durasi lebih dari 15 hari
            if ($durationInHours > (15 * 24)) {
                // Pengecekan apakah hari pertama menstruasi memenuhi minimal masa suci
                if ($intervalInHours >= (15 * 24)) {
                    // Jika sudah melewati masa suci, bagian awal darah adalah haid dan sisanya istihadhah
                    $status = 'Campuran (Haid dan Istihadhah)';
                } else {
                    // Jika awal darah masuk dalam siklus sebelumnya, hitung ulang rentang total durasi
                    $totalCycleDurationInHours = ($lastStartDateTime->diff($endDateTime)->days * 24) + $lastStartDateTime->diff($endDateTime)->h;
    
                    if ($totalCycleDurationInHours > (15 * 24)) {
                        $status = 'Campuran (Haid dan Istihadhah)';
                    } else {
                        $status = 'Haid';
                    }
                }
            } else {
                // Jika durasi minimal 24 jam terpenuhi untuk dianggap sebagai haid
                if ($durationInHours < 24) {
                    // Jika durasi kurang dari 24 jam, status dianggap Istihadhah
                    $status = 'Istihadhah';
                } else {
                    if ($lastMenstruation) {
                        $daysSinceLastMenstruation = $lastEndDateTime->diff($startDateTime)->days;
    
                        if ($daysSinceLastMenstruation < 15) {
                            // Bagian awal adalah Istihadhah sampai masa suci tercapai
                            $daysOfIstihadhah = 15 - $daysSinceLastMenstruation;
                            $istihadhahHours = $daysOfIstihadhah * 24;
    
                            if ($durationInHours > $istihadhahHours) {
                                // Jika durasi melebihi masa istihadhah, maka ada bagian yang Haid
                                $status = 'Campuran (Istihadhah dan Haid)';
                            } else {
                                $status = 'Istihadhah';
                            }
                        } else {
                            // Jika darah keluar setelah masa suci 15 hari terpenuhi
                            $status = 'Haid';
                        }
    
                        // Pengecekan jika darah masih dalam rentang siklus haid sebelumnya
                        if ($startDateTime <= $lastCycleEnd) {
                            // Jika total rentang durasi lebih dari 15 hari
                            $totalCycleDurationInHours = ($lastStartDateTime->diff($endDateTime)->days * 24) + $lastStartDateTime->diff($endDateTime)->h;
                            if ($totalCycleDurationInHours > (15 * 24)) {
                                $status = 'Campuran (Haid dan Istihadhah)';
                            }
                        }
                    }
                }
            }
        }
        return $status;
    }

    public function getDetail($id)
    {
        $menstruation = Menstruation::findOrFail($id);
    
        $durasiHaid = 0;
        $durasiIstihadhah = 0;
    
        if ($menstruation->end_date && $menstruation->end_time) {
            $startDateTime = new \DateTime($menstruation->start_date . ' ' . $menstruation->start_time);
            $endDateTime = new \DateTime($menstruation->end_date . ' ' . $menstruation->end_time);
            $durationInHours = ($startDateTime->diff($endDateTime)->days * 24) + $startDateTime->diff($endDateTime)->h;
    
            // Ambil haid terakhir untuk menghitung interval sejak haid terakhir selesai
            $lastMenstruation = Menstruation::where('user_id', Auth::id())
                ->whereNotNull('end_date')
                ->where('id', '!=', $id) // Pastikan tidak mengambil siklus yang sedang dihitung
                ->orderBy('end_date', 'desc')
                ->first();
    
            $intervalInHoursSinceLastMenstruation = 0;
            $lastStartDateTime = null; // Inisialisasi lastStartDateTime
    
            if ($lastMenstruation) {
                $lastEndDateTime = new \DateTime($lastMenstruation->end_date . ' ' . $lastMenstruation->end_time);
                $lastStartDateTime = new \DateTime($lastMenstruation->start_date . ' ' . $lastMenstruation->start_time);
                $interval = $lastEndDateTime->diff($startDateTime);
                $intervalInHoursSinceLastMenstruation = ($interval->days * 24) + $interval->h;
            }
    
            if ($menstruation->status == 'Campuran (Haid dan Istihadhah)') {
                // Kasus 1: Start Date di luar siklus sebelumnya dan durasi > 15 hari
                if ($lastStartDateTime && $intervalInHoursSinceLastMenstruation >= (15 * 24)) {
                    // Hitung total durasi hanya dari siklus ini
                    $haidHours = min($durationInHours, (15 * 24));
    
                    $durasiHaid = floor($haidHours / 24); // Konversi ke hari
                    $remainingHours = $durationInHours - $haidHours;
    
                    if ($remainingHours > 0) {
                        $durasiIstihadhah = floor($remainingHours / 24); // Sisanya Istihadhah
                    }
                }
                // // Kasus 2: Start Date masih dalam siklus sebelumnya
                elseif ($lastStartDateTime) {
                    // Hitung total durasi dari siklus sebelumnya sampai akhir darah keluar
                    $totalCycleDurationInHours = ($lastStartDateTime->diff($endDateTime)->days * 24) + $lastStartDateTime->diff($endDateTime)->h;

                    // Hitung batas akhir siklus Haid (15 hari dari $lastStartDateTime)
                    $lastCycleEnd = clone $lastStartDateTime;
                    $lastCycleEnd->modify('+15 days');

                    // Hitung durasi Haid (dari Start Date hingga akhir siklus Haid sebelumnya)
                    $haidDurationInHours = 0;
                    if ($startDateTime <= $lastCycleEnd) {
                        // Hitung durasi Haid dari Start Date siklus baru hingga akhir siklus sebelumnya
                        $haidDurationInHours = ($startDateTime->diff($lastCycleEnd)->days * 24) + $startDateTime->diff($lastCycleEnd)->h;

                        // Jika durasi Haid lebih besar dari total durasi darah keluar, batasi ke total durasi
                        $haidDurationInHours = min($haidDurationInHours, $totalCycleDurationInHours);
                    }

                    // Konversi durasi Haid ke hari
                    $durasiHaid = floor($haidDurationInHours / 24);

                    // Hitung durasi Istihadhah (sisa durasi setelah akhir siklus Haid sebelumnya)
                    $istihadhahDurationInHours = 0;
                    if ($endDateTime > $lastCycleEnd) {
                        // Durasi Istihadhah dari akhir siklus Haid hingga End Date
                        $istihadhahDurationInHours = ($lastCycleEnd->diff($endDateTime)->days * 24) + $lastCycleEnd->diff($endDateTime)->h;
                    }

                    // Konversi durasi Istihadhah ke hari
                    if ($istihadhahDurationInHours > 0) {
                        $durasiIstihadhah = floor($istihadhahDurationInHours / 24);
                    }
                }
                
            } elseif ($menstruation->status == 'Campuran (Istihadhah dan Haid)') {
                // Tentukan durasi Istihadhah terlebih dahulu
                $maxIstihadhahHours = min($durationInHours, 5 * 24); // Maksimal 5 hari Istihadhah
    
                $durasiIstihadhah = floor($maxIstihadhahHours / 24);
    
                // Sisanya adalah Haid
                $remainingHours = $durationInHours - $maxIstihadhahHours;
                if ($remainingHours > 0) {
                    $durasiHaid = floor($remainingHours / 24);
                }
            } elseif ($menstruation->status == 'Istihadhah') {
                $durasiIstihadhah = floor($durationInHours / 24);
            } elseif ($menstruation->status == 'Haid') {
                $durasiHaid = floor($durationInHours / 24);
            }
        }
    
        // Ambil salat yang perlu diqada dari prayer_start dan prayer_end
        $qadaSalat = [];
        $prayerKeys = ['prayer_start', 'prayer_end'];
        $allChecked = true;
    
        foreach ($prayerKeys as $prayerKey) {
            if (!empty($menstruation->{$prayerKey})) {
                $qadaSalat[] = [
                    'nama' => ucfirst($menstruation->{$prayerKey}),
                    'diqada' => false
                ];
                $allChecked = false;
            }
        }
    
        return response()->json([
            'durasi_haid' => $durasiHaid,
            'durasi_istihadhah' => $durasiIstihadhah,
            'qada_salat' => $qadaSalat,
            'all_checked' => $allChecked
        ]);
    }
    
    public function updateQadaSalat(Request $request, $id)
    {
        $request->validate([
            'prayer_name' => 'required|string',
        ]);

        // Ambil data menstruation berdasarkan ID
        $menstruation = Menstruation::findOrFail($id);

        // Kolom-kolom yang ingin kita manipulasi
        $prayerKeys = ['prayer_start', 'prayer_end'];

        // Loop melalui setiap kolom (prayer_start dan prayer_end)
        foreach ($prayerKeys as $prayerKey) {
            if (!empty($menstruation->{$prayerKey})) {
                // Jika datanya sesuai dengan nama sholat, kita akan hapus
                if (strtolower($menstruation->{$prayerKey}) === strtolower($request->prayer_name)) {
                    $menstruation->{$prayerKey} = null; // Hapus data sholat dengan mengubah ke NULL
                    $menstruation->save();

                    // Pastikan perubahan sudah tersimpan di database
                    return response()->json(['message' => 'Status qada salat berhasil diperbarui dan salat dihapus.'], 200);
                }
            }
        }

        return response()->json(['message' => 'Salat tidak ditemukan dalam data.'], 404);
    }

    private function calculateDurations($menstruation)
    {
        $durasiHaid = 0;
        $durasiIstihadhah = 0;

        if ($menstruation->end_date && $menstruation->end_time) {
            $startDateTime = new \DateTime($menstruation->start_date . ' ' . $menstruation->start_time);
            $endDateTime = new \DateTime($menstruation->end_date . ' ' . $menstruation->end_time);
            $durationInHours = ($startDateTime->diff($endDateTime)->days * 24) + $startDateTime->diff($endDateTime)->h;

            // Ambil haid terakhir untuk menghitung interval sejak haid terakhir selesai
            $lastMenstruation = Menstruation::where('user_id', Auth::id())
                ->whereNotNull('end_date')
                ->where('id', '!=', $menstruation->id) // Pastikan tidak mengambil siklus yang sedang dihitung
                ->orderBy('end_date', 'desc')
                ->first();

            $intervalInHoursSinceLastMenstruation = 0;
            $lastStartDateTime = null;

            if ($lastMenstruation) {
                $lastEndDateTime = new \DateTime($lastMenstruation->end_date . ' ' . $lastMenstruation->end_time);
                $lastStartDateTime = new \DateTime($lastMenstruation->start_date . ' ' . $lastMenstruation->start_time);
                $interval = $lastEndDateTime->diff($startDateTime);
                $intervalInHoursSinceLastMenstruation = ($interval->days * 24) + $interval->h;
            }

            if ($menstruation->status == 'Campuran (Haid dan Istihadhah)') {
                if ($lastStartDateTime && $intervalInHoursSinceLastMenstruation >= (15 * 24)) {
                    $haidHours = min($durationInHours, (15 * 24));
                    $durasiHaid = floor($haidHours / 24);
                    $remainingHours = $durationInHours - $haidHours;
                    if ($remainingHours > 0) {
                        $durasiIstihadhah = floor($remainingHours / 24);
                    }
                } elseif ($lastStartDateTime) {
                    $totalCycleDurationInHours = ($lastStartDateTime->diff($endDateTime)->days * 24) + $lastStartDateTime->diff($endDateTime)->h;
                    $lastCycleEnd = clone $lastStartDateTime;
                    $lastCycleEnd->modify('+15 days');

                    $haidDurationInHours = 0;
                    if ($startDateTime <= $lastCycleEnd) {
                        $haidDurationInHours = ($startDateTime->diff($lastCycleEnd)->days * 24) + $startDateTime->diff($lastCycleEnd)->h;
                        $haidDurationInHours = min($haidDurationInHours, $totalCycleDurationInHours);
                    }

                    $durasiHaid = floor($haidDurationInHours / 24);

                    $istihadhahDurationInHours = 0;
                    if ($endDateTime > $lastCycleEnd) {
                        $istihadhahDurationInHours = ($lastCycleEnd->diff($endDateTime)->days * 24) + $lastCycleEnd->diff($endDateTime)->h;
                    }

                    if ($istihadhahDurationInHours > 0) {
                        $durasiIstihadhah = floor($istihadhahDurationInHours / 24);
                    }
                }
            } elseif ($menstruation->status == 'Campuran (Istihadhah dan Haid)') {
                $maxIstihadhahHours = min($durationInHours, 5 * 24);
                $durasiIstihadhah = floor($maxIstihadhahHours / 24);
                $remainingHours = $durationInHours - $maxIstihadhahHours;
                if ($remainingHours > 0) {
                    $durasiHaid = floor($remainingHours / 24);
                }
            } elseif ($menstruation->status == 'Istihadhah') {
                $durasiIstihadhah = floor($durationInHours / 24);
            } elseif ($menstruation->status == 'Haid') {
                $durasiHaid = floor($durationInHours / 24);
            }
        }

        return ['durasi_haid' => $durasiHaid, 'durasi_istihadhah' => $durasiIstihadhah];
    }

}

