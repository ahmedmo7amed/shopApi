<?php 

namespace Modules\Payment\Repositories;

use Modules\Payment\Models\Payment;

class PaymentRepository{
    protected Payment $paymentModel;
    
    public function __construct(Payment $paymentModel)
    {
        $this->paymentModel = $paymentModel;
    }
    public function getAllPayments()
    {
        return $this->paymentModel::all();
    }
    public function getPaymentById($id)
    {
        return $this->paymentModel::findOrFail($id);
    }

    public function createPayment($data)
    {
        return $this->paymentModel::create($data);
    }

    public function updatePayment($id, $data)
    {
        $payment = $this->paymentModel::findOrFail($id);
        $payment->update($data);
        return $payment;
    }
    public function deletePayment($id)
    {
        $payment = $this->paymentModel::findOrFail($id);
        return $payment->delete();
    }
    
}