<?php

namespace Modules\Invoice\Services\V1;

use Modules\Invoice\Repositories\V1\InvoiceRepository;
use Modules\Invoice\Resources\V1\InvoiceResource;
use Modules\Invoice\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

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
    public function searchInvoices(array $criteria)
    {
        $query = Invoice::query();

        if (!empty($criteria['invoice_number'])) {
            $query->where('invoice_number', 'LIKE', '%' . $criteria['invoice_number'] . '%');
        }

        if (!empty($criteria['customer_name'])) {
            $query->where('customer_name', 'LIKE', '%' . $criteria['customer_name'] . '%');
        }

        if (!empty($criteria['status'])) {
            $query->where('status', $criteria['status']);
        }

        if (!empty($criteria['from_date'])) {
            $query->whereDate('created_at', '>=', $criteria['from_date']);
        }

        if (!empty($criteria['to_date'])) {
            $query->whereDate('created_at', '<=', $criteria['to_date']);
        }

        return $query->latest()->paginate(15);
    }
   public function exportInvoices(string $format)
    {
        if ($format !== 'pdf') {
            throw new \Exception('Unsupported export format');
        }

        $invoices = Invoice::latest()->get();

        $pdf = Pdf::loadView('pdf.invoices', compact('invoices'));

        $filePath = storage_path('app/invoices.pdf');
        $pdf->save($filePath);

        return $filePath;
    }
}
