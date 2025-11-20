<?php 

namespace Modules\Payment\Services;

use App\Filament\Resources\PaymentResource;
use Modules\Payment\Repositories\PaymentRepository;

class PaymentServices{

    protected PaymentRepository $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }
    public function getAllPayments()
    {
        $payments = $this->paymentRepository->getAllPayments();
        return PaymentResource::collection($payments);
    }
    public function getPaymentById($id)
    {
        $payment = $this->paymentRepository->getPaymentById($id);
        return new PaymentResource($payment);
    }
    public function createPayment($data)
    {
        $payment = $this->paymentRepository->createPayment($data);
        return new PaymentResource($payment);
    }
    public function updatePayment($id, $data)
    {
        $payment = $this->paymentRepository->updatePayment($id, $data);
        return new PaymentResource($payment);
    }
    public function deletePayment($id)
    {
        $this->paymentRepository->deletePayment($id);
    }
    
}
