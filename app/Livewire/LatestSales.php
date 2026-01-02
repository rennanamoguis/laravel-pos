<?php

namespace App\Livewire;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Sale;
use Filament\Tables\Columns\TextColumn;

class LatestSales extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Sale::query()->with(['customer','saleItems']))
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Customer Name')
                    ->sortable(),
                TextColumn::make('saleItems.item.name')
                    ->label('Sold Items')
                    ->bulleted()
                    ->limitList(2)
                    ->expandableLimitedList(),
                TextColumn::make('total')
                    ->label('Total Amount')
                    ->money('php',true)
                    ->sortable(),
                TextColumn::make('discount')
                    ->label('Discount')
                    ->money('php',true),
                TextColumn::make('paid_amount')
                    ->label('Amount Paid')
                    ->money('php',true),
                TextColumn::make('paymentMethod.name')
                    ->label('Payment Method'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
