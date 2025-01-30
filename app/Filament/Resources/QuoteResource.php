<?php
namespace App\Filament\Resources;

use App\Filament\Resources\QuoteResource\Pages;
use App\Models\Product;
use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('contact_name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required(),
                        Forms\Components\TextInput::make('tax_number')
                            ->label('Tax ID/VAT Number'),
                    ])->columns(2),
                Forms\Components\TextInput::make('address')
                    ->label('Address')
                    ->required(),
                Forms\Components\Section::make('Product Details')
                    ->schema([
                        Forms\Components\Repeater::make('products')
                            ->relationship('products') // إشارة صريحة للعلاقة
                            ->schema([
                                // اختيار منتج موجود
                                Forms\Components\Select::make('product_id') // الاسم يجب أن يكون `product_id`
                                ->label('Product')
                                    ->options(Product::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('unit_price', $product->price);
                                            $set('tax_rate', $product->default_tax_rate);
                                        }
                                    })
                                    ->columnSpan(2),

                                // الحقول الخاصة بالجدول الوسيط
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1),

                                Forms\Components\TextInput::make('unit_price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),

                                Forms\Components\Select::make('tax_rate')
                                    ->options([
                                        '0' => '0%',
                                        '5' => '5%',
                                        '10' => '10%',
                                        '15' => '15%',
                                    ])
                                    ->required(),
                            ])
                            ->columns(4)
                            ->createItemButtonLabel('Add Product')
                            ->required(),
                    ]),
                Forms\Components\Section::make('Pricing Summary')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->numeric()
                            ->prefix('$')
                            ->readOnly()
                            ->default(0),
                        Forms\Components\TextInput::make('tax_total')
                            ->numeric()
                            ->prefix('$')
                            ->readOnly()
                            ->default(0),
                        Forms\Components\TextInput::make('grand_total')
                            ->numeric()
                            ->prefix('$')
                            ->readOnly()
                            ->default(0),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Terms & Conditions')
                    ->schema([
                        Forms\Components\DatePicker::make('expiration_date')
                            ->required()
                            ->minDate(now()),
                        Forms\Components\Textarea::make('special_notes')
                            ->label('Additional Notes'),
                        Forms\Components\Textarea::make('payment_terms')
                            ->default('Net 30 days from invoice date'),
                    ]),
            ]);
    }

    protected static function updateTotals(callable $get, callable $set): void
    {
        $products = $get('products');

        $subtotal = collect($products)->reduce(function ($carry, $item) {
            return $carry + ($item['quantity'] * $item['unit_price']);
        }, 0);

        $taxTotal = collect($products)->reduce(function ($carry, $item) {
            return $carry + (($item['quantity'] * $item['unit_price']) * ($item['tax_rate'] / 100));
        }, 0);

        $grandTotal = $subtotal + $taxTotal;

        $set('subtotal', number_format($subtotal, 2));
        $set('tax_total', number_format($taxTotal, 2));
        $set('grand_total', number_format($grandTotal, 2));
    }
    public static function table(Table $table): Table
                            {
                                return $table
                                    ->columns([
                                        Tables\Columns\TextColumn::make('company_name')
                                            ->searchable(),
                                        Tables\Columns\TextColumn::make('contact_name'),
                                        Tables\Columns\TextColumn::make('expiration_date')
                                            ->date(),
                                        Tables\Columns\TextColumn::make('grand_total')
                                            ->money('USD')
                                            ->sortable(),
                                        Tables\Columns\TextColumn::make('products.name')
                                            ->badge()
                                            ->separator(',')
                                            ->limitList(3),
                                        Tables\Columns\TextColumn::make('created_at')
                                            ->dateTime(),
                                    ])
                                    ->filters([
                                        Tables\Filters\TrashedFilter::make(),
                                        Tables\Filters\Filter::make('expired')
                                            ->query(fn (Builder $query) => $query->where('expiration_date', '<', now())),
                                    ])
                                    ->actions([
                                        Tables\Actions\Action::make('pdf')
                                            ->label('PDF')
                                            ->icon('heroicon-o-document-arrow-down')
                                            ->action(function (Quote $record) {
                                                $record->load('products'); // Eager load products with pivot data
                                                $pdf = Pdf::loadView('quotes.pdf',
                                                    [
                                                        'quote' => $record,
                                                        'subtotal' => $record->subtotal,
                                                        'tax_total' => $record->tax_total,
                                                        'grand_total' => $record->grand_total

                                                    ]);

                                                return response()->streamDownload(
                                                    fn () => print($pdf->output()),
                                                    "quote-{$record->id}.pdf",
                                                    [
                                                        'Content-Type' => 'application/pdf',
                                                        'Content-Disposition' => 'inline; filename="quote-'.$record->id.'.pdf"'
                                                    ]
                                                );
                                            }),
                                        Tables\Actions\EditAction::make(),
                                        Tables\Actions\DeleteAction::make(),
                                        Tables\Actions\RestoreAction::make(),
                                    ])
                                    ->bulkActions([
                                        Tables\Actions\BulkActionGroup::make([
                                            Tables\Actions\DeleteBulkAction::make(),
                                            Tables\Actions\ForceDeleteBulkAction::make(),
                                            Tables\Actions\RestoreBulkAction::make(),
                                        ]),
                                    ]);
                            }

                            public static function getEloquentQuery(): Builder
                            {
                                return parent::getEloquentQuery()
                                    ->withoutGlobalScopes([
                                        SoftDeletingScope::class,
                                    ]);
                            }

                            public static function getPages(): array
                            {
                                return [
                                    'index' => Pages\ListQuotes::route('/'),
                                    'create' => Pages\CreateQuote::route('/create'),
                                    'edit' => Pages\EditQuote::route('/{record}/edit'),
                                ];
                            }
                        }
