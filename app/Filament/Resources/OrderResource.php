<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Shop Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_number')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->rules(['regex:/^ORD-[A-Z0-9]{10}$/']),
                Forms\Components\Select::make('customer_id')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->orderBy('name')
                    )
                    ->required()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                    ])
                    ->createOptionAction(fn (Action $action) => $action->modalWidth('xl')),
                Forms\Components\DateTimePicker::make('order_date')
                    ->required()
                    ->default(now()),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending'),
                Forms\Components\Textarea::make('shipping_address')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\TextInput::make('shipping_method')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('shipping_cost')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->default(0),
                Forms\Components\TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('tax')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->default(0),
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $product = Product::find($state);
                                if ($product) {
                                    $set('price', $product->price);
                                }
                            }),
                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Forms\Components\Section::make('Order History')
                    ->schema([
                        Forms\Components\Repeater::make('history')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'processing' => 'Processing',
                                        'shipped' => 'Shipped',
                                        'delivered' => 'Delivered',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->required(),
                                Forms\Components\DateTimePicker::make('created_at')
                                    ->default(now())
                                    ->required(),
                                Forms\Components\Textarea::make('notes'),
                            ])
                            ->columns(3)
                    ]),
                Forms\Components\Repeater::make('history') // Must match method name
                ->relationship()
                    ->schema([/* ... */]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'processing' => 'warning',
                        'cancelled' => 'danger',
                        default => 'info',
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
