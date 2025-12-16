<?php

namespace Modules\Customer\Repositories;

use Modules\Customer\Models\Customer;

class CustomerRepository
{
    protected  Customer $customerModel;

    public function __construct(Customer $customerModel)
    {
        $this->customerModel = $customerModel;
    }

    public function getAllCustomers()
    {
        return $this->customerModel->all();
    }

    public function getCustomerById($id)
    {
        return $this->customerModel->findOrFail($id);
    }

    public function createCustomer($data)
    {
        return $this->customerModel->create($data);
    }

    public function updateCustomer($id, $data)
    {
        return $this->customerModel->findOrFail($id)->update($data);
    }

    public function deleteCustomer($id)
    {
        return $this->customerModel->findOrFail($id)->delete();
    }
    
}
