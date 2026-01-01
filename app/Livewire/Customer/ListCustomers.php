<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;

class ListCustomers extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Customer::query())
            ->columns([
                TextColumn::make('name')->label('Customer Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')->label('Email Address')
                    ->searchable(),
                TextColumn::make('phone')->label('Phone Number'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->label('New Customer')
                    ->url(fn (): string => route('customer.create')),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('')
                    ->iconButton()
                    ->tooltip('Edit Customer')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (Customer $record): string => route('customer.edit', $record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.customer.list-customers');
    }
}
