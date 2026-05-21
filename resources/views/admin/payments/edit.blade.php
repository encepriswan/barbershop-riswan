@extends('layouts.app')

@section('content')

<h1 class="text-xl font-bold mb-4">Edit Payment</h1>

<form method="POST" action="{{ route('admin.payments.update',$payment->id) }}" class="bg-white p-6 rounded shadow max-w-md">
@csrf
@method('PUT')

<select name="transaction_id" class="w-full mb-3 p-2 border rounded">
@foreach($transactions as $t)
<option value="{{ $t->id }}" {{ $payment->transaction_id == $t->id ? 'selected' : '' }}>
Transaksi #{{ $t->id }}
</option>
@endforeach
</select>

<input name="method" value="{{ $payment->method }}" class="w-full mb-3 p-2 border rounded">

<select name="status" class="w-full mb-3 p-2 border rounded">
<option value="pending" {{ $payment->status=='pending'?'selected':'' }}>Pending</option>
<option value="paid" {{ $payment->status=='paid'?'selected':'' }}>Paid</option>
</select>

<button class="bg-green-600 text-white w-full py-2 rounded">Update</button>

</form>

@endsection