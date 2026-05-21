<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST + SEARCH + FILTER
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Booking::with('user', 'services');

        // SEARCH
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // FILTER
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->get();

        // AJAX → JSON
        if ($request->ajax()) {
            return response()->json($bookings);
        }

        return view('admin.booking.index', compact('bookings'));
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Booking $booking)
    {
        $services = Service::all();

        return view('admin.booking.edit', compact('booking', 'services'));
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'services' => 'required|array',
            'date' => 'required|date',
            'time' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $booking->update([
                'booking_date' => $request->date,
                'booking_time' => $request->time,
            ]);

            $booking->services()->sync($request->services);

            DB::commit();

            return redirect()->route('admin.booking.index')
                ->with('success', 'Booking berhasil diupdate');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return back()->with('success', 'Booking dihapus');
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS FLOW UNTUK ADMIN)
    |--------------------------------------------------------------------------
    */

    public function approve(Booking $booking)
    {
        $booking->update(['status' => 'approved']);
        return back()->with('success', 'Booking disetujui');
    }

    public function reject(Booking $booking)
    {
        $booking->update(['status' => 'rejected']);
        return back()->with('success', 'Booking ditolak');
    }

    public function checkin(Booking $booking)
    {
        $booking->update(['status' => 'checked_in']);
        return back()->with('success', 'User check-in');
    }

    public function start(Booking $booking)
    {
        $booking->update(['status' => 'in_progress']);
        return back()->with('success', 'Layanan dimulai');
    }

    public function finish(Booking $booking)
    {
        DB::beginTransaction();

        try {

            $booking->update(['status' => 'done']);

            if (!$booking->transaction) {

                $total = $booking->services->sum('price');

                $booking->transaction()->create([
                    'user_id' => $booking->user_id,
                    'total_price' => $total,
                    'status' => 'unpaid'
                ]);
            }

            DB::commit();

            return back()->with('success', 'Booking selesai');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
}
