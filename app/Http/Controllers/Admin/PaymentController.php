<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST PAYMENT
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $payments = Payment::with([
            'transaction.user',
            'transaction.booking.services'
        ])->latest()->get();

        return view('admin.payments.index', compact('payments'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE (FROM TRANSACTION)
    |--------------------------------------------------------------------------
    */
    public function create($transactionId)
    {
        $transaction = Transaction::with('booking.service', 'user')
            ->findOrFail($transactionId);

        return view('admin.payments.create', compact('transaction'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE PAYMENT
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'method' => 'required|string',
            'proof' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('payments', 'public');
        }

        $status = $request->method === 'cash' ? 'paid' : 'pending';

        $payment = Payment::create([
            'transaction_id' => $transaction->id,
            'method' => $request->method,
            'status' => $status,
            'proof' => $proofPath
        ]);

        if ($status === 'paid') {
            $transaction->update([
                'status' => 'paid'
            ]);
        }

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Pembayaran berhasil dibuat');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT PAYMENT
    |--------------------------------------------------------------------------
    */
    public function edit(Payment $payment)
    {
        $transactions = Transaction::all();

        return view('admin.payments.edit', compact('payment', 'transactions'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PAYMENT
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Payment $payment)
    {

        $request->validate([
            'status' => 'required|in:paid,rejected'
        ]);


        $payment->update([
            'status' => $request->status
        ]);


        if ($request->status === 'paid') {
            $payment->transaction->update([
                'status' => 'paid'
            ]);
        } else {
            $payment->transaction->update([
                'status' => 'unpaid'
            ]);
        }

        return back()->with('success', 'Status pembayaran berhasil diupdate');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE PAYMENT
    |--------------------------------------------------------------------------
    */
    public function destroy(Payment $payment)
    {
        // hapus file jika ada
        if ($payment->proof) {
            Storage::disk('public')->delete($payment->proof);
        }

        $payment->delete();

        return back()->with('success', 'Payment dihapus');
    }
}
