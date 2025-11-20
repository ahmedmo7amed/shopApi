<?php

namespace Modules\Invoice\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Modules\Invoice\Services\V1\InvoiceServices;

class InvoiceController extends Controller
{
    protected InvoiceServices $invoiceServices;

    public function __construct(InvoiceServices $invoiceServices)
    {
        $this->invoiceServices = $invoiceServices;
    }
        
    public function getAllInvoices()
    {
        return $this->invoiceServices->getAllInvoices();
    }
    public function getInvoiceById($id)
    {
        return $this->invoiceServices->getInvoiceById($id);
    }
    public function createInvoice($data)
    {
        return $this->invoiceServices->createInvoice($data);
    }
    public function updateInvoice($id, $data)
    {
        return $this->invoiceServices->updateInvoice($id, $data);
    }
    public function deleteInvoice($id)
    {
        return $this->invoiceServices->deleteInvoice($id);
    }
}