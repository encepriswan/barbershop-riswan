<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with([
            'booking.user',
            'booking.services'
        ]);

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('booking.user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                })
                    ->orWhereHas('booking.services', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%$search%");
                    });
            });
        }

        $transactions = $query->latest()->get();

        $totalRevenue = $transactions
            ->where('status', 'paid')
            ->sum('total_price');

        return view('admin.transactions.index', compact(
            'transactions',
            'totalRevenue'
        ));
    }

    public function export(Request $request)
    {
        $query = Transaction::with([
            'booking.user',
            'booking.services'
        ])->where('status', 'paid');

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transactions = $query->get();

        return new StreamedResponse(function () use ($transactions) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['User', 'Service', 'Tanggal', 'Total']);

            foreach ($transactions as $t) {
                fputcsv($handle, [
                    $t->booking->user->name ?? '-',
                    $t->service_names,
                    $t->created_at->format('Y-m-d'),
                    $t->total_price
                ]);
            }

            fclose($handle);
        }, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=laporan-transaksi.csv"
        ]);
    }
}
