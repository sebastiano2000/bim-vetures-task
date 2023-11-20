<?php

namespace App\Http\Requests;

use App\Enums\TransactionStatus;
use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'vat' => 'required|numeric|min:0|max:100',
            'is_vat_inclusive' => 'required|boolean',
            'status' => 'required|in:' . implode(',', [
                TransactionStatus::Paid,
                TransactionStatus::Outstanding,
                TransactionStatus::Overdue,
            ]),
        ];
    }
}
