<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\Utils\Json;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use Json;

    public function index($transaction_id)
    {
        $payments = Payment::query()->where('transaction_id', $transaction_id)->paginate(10);

        return $this->result([ 'payments' => $payments ], 200);
    }

    public function store(PaymentRequest $request)
    {
        $payment = Payment::create($request->validated());

        $payment->transaction->updateStatus();

        return $this->result([ 'payment' => $payment ], 200);
    }

    public function show(Payment $payment)
    {
        return $this->result([ 'payment' => $payment ], 200);
    }

    public function update(PaymentRequest $request, Payment $payment)
    {
        $payment->update($request->validated());

        $payment->transaction->updateStatus();

        return $this->result([ 'payment' => $payment ], 200);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        $payment->transaction->updateStatus();

        return $this->result([], 200);
    }
}
