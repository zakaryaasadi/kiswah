<?php

namespace App\Repositories;

use App\Transaction;
use App\Repositories\BaseRepository;

class TransactionRepository extends BaseRepository
{
    public $COMPLETED = 'COMPLETED';
    public $INCOMPLETE = 'AMOUNT INCOMPLETE';
    public $CANCELLED = 'CANCELLED';
    public $NOT_STARTED = 'NOT_STARTED';

    public function __construct(Transaction $transaction)
    {
        parent::__construct($transaction);
        $this->transaction = $transaction;
    }

    public function logTransaction($amount, array $data, object $invoice)
    {
        $transaction = [
            'reference_id' => $invoice->id,
            'amount' => $invoice->amount,
            'amount_paid' => $amount,
            'nbiss_ref' => $data['PaymentReference'],
            'sessionID' => $data['SessionID'],
            'response' => json_encode($data),
        ];
        return $this->create($transaction);
    }

    public function logPaystack($amount, $ref_id, object $invoice, $data)
    {
        $transaction = [
            'reference_id' => $invoice->id,
            'amount' => $invoice->amount,
            'amount_paid' => $amount,
            'nbiss_ref' => 'PAYSTACK',
            'sessionID' => $ref_id,
            'response' => json_encode($data),
            'status' => 'PENDING'
        ];
        return $this->create($transaction);
    }

    public function updatePaystackRef($ref_id, array $data)
    {
        $transaction = $this->model->where('sessionID', $ref_id)->first();
        if ($transaction) {
            $transaction->update(['response' => json_encode($data), 'status' => $this->COMPLETED]);
        }
        return $transaction;
    }
}
