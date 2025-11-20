<?php

namespace Modules\Customer\Services\V1;


use Modules\Customer\Repositories\CustomerRepository;

class CustomerServices
{
    protected CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getAllCustomers()
    {
        return $this->customerRepository->getAllCustomers();
    }

    public function getCustomerById($id)
    {
        return $this->customerRepository->getCustomerById($id);
    }

    public function createCustomer($data)
    {
        return $this->customerRepository->createCustomer($data);
    }

    public function updateCustomer($id, $data)
    {
        return $this->customerRepository->updateCustomer($id, $data);
    }

    public function deleteCustomer($id)
    {
        return $this->customerRepository->deleteCustomer($id);
    }
}