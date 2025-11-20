<?php

namespace Modules\Invoice\Repositories\V1;
use Modules\Invoice\Models\Invoice;

class InvoiceRepository
    {
     
        protected  Invoice $invoiceModel;

        public function __construct(Invoice $invoiceModel)
        {
            $this->invoiceModel = $invoiceModel;
        }
        
        public function getAllInvoices()
        {
            return $this->invoiceModel->all();
        }
        public function getInvoiceById($id)
        {
           
            return $this->invoiceModel->findOrFail($id);
        }
        public function createInvoice($data)
        {
            return $this->invoiceModel->create($data);
        }
        public function updateInvoice($id, $data)
        {
            return $this->invoiceModel->findOrFail($id)->update($data);
        }
        public function deleteInvoice($id)
        {
            return $this->invoiceModel->findOrFail($id)->delete();
        }
   
    }
