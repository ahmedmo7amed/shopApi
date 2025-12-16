<?php

namespace Modules\Invoice\Http\Controllers\V1;

use App\Filament\Resources\InvoiceResource;
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
        $invoices = $this->invoiceServices->getAllInvoices();
        return response()->json(InvoiceResource::collection($invoices)
        , 200);
    }
    public function getInvoiceById($id)
    {
        $invoice = $this->invoiceServices->getInvoiceById($id);
         return response()->json(new InvoiceResource($invoice), 200);
    }
    public function createInvoice($data)
    {
        $this->invoiceServices->createInvoice($data);
        return response()->json(['message' =>
        'Invoice created successfully'], 201);
        //return $this->invoiceServices->createInvoice($data);
    }
    public function updateInvoice($id, $data)
    {
       $this->invoiceServices->updateInvoice($id, $data);
         return response()->json(['message' =>
        'Invoice updated successfully'], 200);
    }
    public function deleteInvoice($id)
    {
        $this->invoiceServices->deleteInvoice($id);
        return response()->json(['message' =>
        'Invoice deleted successfully'], 200);
    }
    public function searchInvoices($criteria)
    {
        $invoices = $this->invoiceServices->searchInvoices($criteria);
        return response()->json(InvoiceResource::collection($invoices)
        , 200);
    }
    public function exportInvoices($format)
    {
        $filePath = $this->invoiceServices->exportInvoices($format);
        return response()->download($filePath);
    }
}
