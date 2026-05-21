<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\Booking;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX (TAMPIL ANTRIAN)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $bookings = Booking::with('user')

            ->whereDate('booking_date', today())

            ->whereIn('status', [
                'checked_in',
                'in_progress'
            ])

            ->orderBy('booking_time')
            ->get();

        return view('admin.queue.index', compact('bookings'));
    }

    /*
    |--------------------------------------------------------------------------
    | ASSIGN KE KURSI TUNGGU (CHECK-IN)
    |--------------------------------------------------------------------------
    */
    public function assign(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id'
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->seat_number) {
            return back()->with('error', 'Booking sudah punya kursi');
        }

        $usedSeats = Booking::where('status', 'checked_in')
            ->pluck('seat_number')
            ->filter();

        $seat = collect(range(1, 10))->diff($usedSeats)->first();

        if (!$seat) {
            return back()->with('error', 'Kursi penuh');
        }

        $booking->update([
            'status' => 'checked_in',
            'seat_number' => $seat
        ]);

        return back()->with('success', 'Masuk kursi tunggu');
    }

    /*
    |--------------------------------------------------------------------------
    | PINDAH KE BARBER (IN PROGRESS)
    |--------------------------------------------------------------------------
    */
    public function start(Booking $booking)
    {
        if ($booking->status !== 'checked_in') {
            return back()->with('error', 'Harus check-in dulu');
        }

        $used = Booking::where('status', 'in_progress')
            ->pluck('seat_number')
            ->filter();

        $seat = collect(range(1, 4))->diff($used)->first();

        if (!$seat) {
            return back()->with('error', 'Kursi barber penuh');
        }

        $booking->update([
            'status' => 'in_progress',
            'seat_number' => $seat
        ]);

        return back()->with('success', 'Mulai layanan');
    }

    /*
    |--------------------------------------------------------------------------
    | SELESAI (KURSI JADI KOSONG - TIDAK GESER)
    |--------------------------------------------------------------------------
    */
    public function finish(Booking $booking)
    {
        if ($booking->status !== 'in_progress') {
            return back()->with('error', 'Belum diproses');
        }

        $booking->update([
            'status' => 'done',
            'seat_number' => null
        ]);

        return back()->with('success', 'Selesai');
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAR (OPSIONAL - LEGACY)
    |--------------------------------------------------------------------------
    */
    public function clear($id)
    {
        $queue = Queue::findOrFail($id);

        $queue->update([
            'booking_id' => null,
            'status' => 'empty'
        ]);

        return back();
    }
}
