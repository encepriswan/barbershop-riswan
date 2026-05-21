<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | USER AREA
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $userId = Auth::id();

        $bookings = Booking::with('services')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $transactions = Transaction::where('user_id', $userId)->get();

        return view('dashboard', compact('bookings', 'transactions'));
    }

    public function userIndex()
    {
        $bookings = Booking::with(['services', 'transaction'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('booking.index', compact('bookings'));
    }

    public function create()
    {
        $services = Service::all();
        return view('booking.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
            'date' => 'required',
            'time' => 'required',
        ]);

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'booking_date' => $request->date,
            'booking_time' => $request->time,
            'status' => 'pending'
        ]);


        $booking->services()->attach($request->services);


        $total = $booking->services()->sum('price');


        \App\Models\Transaction::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'total_price' => $total,
            'status' => 'unpaid'
        ]);

        return redirect()->route('booking.index')
            ->with('success', 'Booking berhasil dibuat');
    }

    public function userEdit(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() || !$booking->isPending()) {
            return back()->with('error', 'Tidak bisa edit booking');
        }

        $services = Service::all();
        return view('booking.edit', compact('booking', 'services'));
    }

    public function userUpdate(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id() || !$booking->isPending()) {
            return back()->with('error', 'Tidak bisa update');
        }

        $request->validate([
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
            'date' => 'required',
            'time' => 'required'
        ]);

        $booking->update([
            'booking_date' => $request->date,
            'booking_time' => $request->time,
        ]);

        $booking->services()->sync($request->services);

        return redirect()->route('booking.index')
            ->with('success', 'Booking diupdate');
    }

    public function userDelete(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() || !$booking->isPending()) {
            return back()->with('error', 'Tidak bisa hapus');
        }

        $booking->delete();

        return back()->with('success', 'Booking dihapus');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN AREA
    |--------------------------------------------------------------------------
    */

    public function adminDashboard()
    {
        $bookings = Booking::with(['user', 'services'])->latest()->get();

        return view('admin.dashboard', compact('bookings'));
    }

    public function index()
    {
        $bookings = Booking::with(['user', 'services'])->latest()->get();

        return view('admin.booking.index', compact('bookings'));
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS FLOW
    |--------------------------------------------------------------------------
    */

    public function approve(Booking $booking)
    {
        $booking->update(['status' => 'approved']);
        return back()->with('success', 'Booking disetujui');
    }

    public function reject(Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);
        return back()->with('success', 'Booking ditolak');
    }

    public function checkin(Booking $booking)
    {
        if (!$booking->isApproved()) {
            return back()->with('error', 'Belum di-approve');
        }

        $booking->update(['status' => 'checked_in']);

        return back()->with('success', 'Customer check-in');
    }

    public function start(Booking $booking)
    {
        // cari kursi kosong (1-4)
        $usedSeats = Booking::where('status', 'in_progress')
            ->pluck('seat_number')
            ->toArray();

        $seat = collect([1, 2, 3, 4])->first(function ($s) use ($usedSeats) {
            return !in_array($s, $usedSeats);
        });

        if (!$seat) {
            return back()->with('error', 'Kursi penuh');
        }

        $booking->update([
            'status' => 'in_progress',
            'seat_number' => $seat
        ]);

        return back()->with('success', 'Masuk kursi ' . $seat);
    }

    public function finish(Booking $booking)
    {
        if (!$booking->isInProgress()) {
            return back()->with('error', 'Belum mulai layanan');
        }
        $total = $booking->services->sum('price');

        $booking->update([
            'status' => 'done',
            'seat_number' => null
        ]);

        if (!$booking->transaction) {
            \App\Models\Transaction::create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'total_price' => $total,
                'status' => 'unpaid'
            ]);
        }

        return back()->with('success', 'Layanan selesai');
    }

    public function queue()
    {
        $bookings = \App\Models\Booking::with('user', 'services')
            ->whereDate('booking_date', today())
            ->orderBy('booking_time')
            ->get();

        return view('queue.index', compact('bookings'));
    }
}
