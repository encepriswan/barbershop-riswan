<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN BAYAR
    |--------------------------------------------------------------------------
    */
    public function userCreate($transactionId)
    {
        $transaction = Transaction::with('booking.services')
            ->where('user_id', Auth::id())
            ->where('status', 'unpaid')
            ->findOrFail($transactionId);

        // CEK DOUBLE PAYMENT
        if ($transaction->payments()->exists()) {
            return redirect()->route('booking.index')
                ->with('error', 'Pembayaran sudah dikirim, tunggu verifikasi admin.');
        }

        return view('payment.create', compact('transaction'));
    }


    /*
    |--------------------------------------------------------------------------
    | KIRIM PEMBAYARAN
    |--------------------------------------------------------------------------
    */
    public function userStore(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'method' => 'required|in:cash,transfer,ewallet,qris',
            'ewallet_type' => 'required_if:method,ewallet|in:gopay,ovo,dana,shopeepay',
            'proof' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        DB::beginTransaction();

        try {
            $transaction = Transaction::where('id', $request->transaction_id)
                ->where('user_id', Auth::id())
                ->where('status', 'unpaid')
                ->firstOrFail();

            if ($transaction->payments()->exists()) {
                return back()->with('error', 'Pembayaran sudah pernah dikirim');
            }
            $method = $this->resolveMethod($request);
            $path = null;

            if ($request->hasFile('proof')) {
                $path = $request->file('proof')->store('payments', 'public');
            }

            Payment::create([
                'transaction_id' => $transaction->id,
                'method' => $method,
                'status' => Payment::STATUS_PENDING,
                'proof' => $path,
                'note' => $request->method === 'cash'
                    ? 'Bayar di tempat (menunggu konfirmasi admin)'
                    : null
            ]);

            DB::commit();

            return redirect()->route('booking.index')
                ->with('success', 'Pembayaran berhasil dikirim, menunggu konfirmasi admin');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }


    /*
    |--------------------------------------------------------------------------
    | HELPER METHOD
    |--------------------------------------------------------------------------
    */
    private function resolveMethod(Request $request)
    {
        if ($request->method === 'ewallet') {
            return 'ewallet_' . $request->ewallet_type;
        }

        return $request->method;
    }
}
