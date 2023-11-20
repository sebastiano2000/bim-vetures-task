<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Utils\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    use Json;
    
    public function index(Request $request)
    {
        $transactions = Transaction::query()->paginate(10);

        return $this->result([ 'transactions' => $transactions ], 200);
    }

    public function store(TransactionRequest $request)
    {
        $transaction = Transaction::create($request->validated());

        $transaction->updateStatus();

        return $this->result([ 'transaction' => $transaction ], 200);
    }

    public function show(Transaction $transaction)
    {
        return $this->result([ 'transaction' => $transaction ], 200);
    }

    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->validated());

        $transaction->updateStatus();

        return $this->result([ 'transaction' => $transaction ], 200);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return $this->result([], 200);
    }

    public function getReport(ReportRequest $request)
    {
        $transactions = DB::table('transactions')
            ->select([
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(IF(status = "paid", amount, 0)) as paid'),
                DB::raw('SUM(IF(status = "outstanding", amount, 0)) as outstanding'),
                DB::raw('SUM(IF(status = "overdue", amount, 0)) as overdue'),
            ])
            ->whereBetween('created_at', [ $request->start_date, $request->end_date ])
            ->groupBy('year', 'month')
            ->get();

        return $this->result([ 'transactions' => $transactions ], 200);
    }

    public function getUserTransaction(Request $request)
    {
        $transactions = Transaction::query()->where('user_id', auth()->user()->id)->paginate(10);

        return $this->result([ 'transactions' => $transactions ], 200);
    }
}
