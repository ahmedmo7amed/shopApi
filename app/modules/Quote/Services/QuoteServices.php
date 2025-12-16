<?php

namespace Modules\Quote\Services;

use Modules\Quote\Repositories\QuoteRepositories;
use Modules\Quote\Resources\V1\QuoteResources;
use Modules\Quote\Models\Quote;


class QuoteServices
{
    protected QuoteRepositories $quoteRepository;

    public function __construct(QuoteRepositories $quoteRepository)
    {
        $this->quoteRepository = $quoteRepository;
    }

    public function getAllQuotes($filters = [], $perPage = 12)
    {
        $quotes = $this->quoteRepository->getAllQuotes($filters, $perPage);
        return QuoteResources::collection($quotes);
    }

    public function getQuoteById($id)
    {
        $quote = $this->quoteRepository->getQuoteById($id);
        return new QuoteResources($quote);
    }

    public function createQuote($data)
    {
        $quote = $this->quoteRepository->createQuote($data);
        return new QuoteResources($quote);
    }

    public function updateQuote($id, $data)
    {
        $quote = $this->quoteRepository->updateQuote($id, $data);
        return new QuoteResources($quote);
    }

    public function deleteQuote($id)
    {
        $this->quoteRepository->deleteQuote($id);
    }
}
