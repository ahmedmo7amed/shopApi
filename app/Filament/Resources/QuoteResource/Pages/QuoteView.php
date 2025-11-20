<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use App\Models\Quote;
use Filament\Resources\Pages\Page;

class QuoteView extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'emails.quotes.generated';
    protected static ?string $slug = 'quote-email';
    public ?Quote $quote = null;
    public function mount($quoteId){
        $this->quote = Quote::with('products')->findOrFail($quoteId);

    }
    public function getViewData(): array
    {
        return ['quote' => $this->quote];
    }
}
