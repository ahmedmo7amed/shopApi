<?php

namespace Modules\Invoice\Services\V1;

use Modules\Invoice\Repositories\V1\InvoiceRepository;
use Modules\Invoice\Resources\V1\InvoiceResource;

class InvoiceServices
{
    protected InvoiceRepository $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    
    public function getAllInvoices()
    {
        return $this->invoiceRepository->getAllInvoices();
    }

    public function getInvoiceById($id)
    {
        return $this->invoiceRepository->getInvoiceById($id);
    }

    public function createInvoice($data)
    {
        return $this->invoiceRepository->createInvoice($data);
    }

    public function updateInvoice($id, $data)
    {
        return $this->invoiceRepository->updateInvoice($id, $data);
    }

    public function deleteInvoice($id)
    {
        return $this->invoiceRepository->deleteInvoice($id);
    }
}