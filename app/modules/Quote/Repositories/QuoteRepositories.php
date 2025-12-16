<?php

namespace Modules\Quote\Repositories;
use Modules\Quote\Models\Quote;

class QuoteRepositories
{
    public function getAllQuotes($filters = [], $perPage = 12)
    {
        $query = Quote::query();

        // Apply filters if any
        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        return $query->paginate($perPage);
    }

    public function getQuoteById($id)
    {
        return Quote::findOrFail($id);
    }

    public function createQuote($data)
    {
        return Quote::create($data);
    }

    public function updateQuote($id, $data)
    {
        $quote = Quote::findOrFail($id);
        $quote->update($data);
        return $quote;
    }

    public function deleteQuote($id)
    {
        $quote = Quote::findOrFail($id);
        $quote->delete();
    }
}
