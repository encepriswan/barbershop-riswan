<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('user', 'services')->latest()->take(10)->get();

        $totalBooking = Booking::count();
        $pending = Booking::where('status', 'pending')->count();
        $approved = Booking::where('status', 'approved')->count();
        $done = Booking::where('status', 'done')->count();

        $today = Booking::whereDate('booking_date', now())->count();

        $revenue = Transaction::where('status', 'paid')->sum('total_price');

        $paymentPending = Payment::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'bookings',
            'totalBooking',
            'pending',
            'approved',
            'done',
            'today',
            'revenue',
            'paymentPending'
        ));
    }
}
