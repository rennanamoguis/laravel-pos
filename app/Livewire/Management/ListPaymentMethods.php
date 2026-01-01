<?php

namespace App\Livewire\Management;

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
use App\Models\PaymentMethod;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class ListPaymentMethods extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => PaymentMethod::query())
            ->columns([
                TextColumn::make('name')->label('Payment Method')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->label('New Payment Method')
                    ->url(fn (): string => route('payment.method.create')),
            ])
            ->recordActions([
                Action::make('delete')
                    ->label('')              // no "Delete" text
                    ->iconButton()           // render as icon-only button
                    ->tooltip('Delete')      // optional: still show hint on hover
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading(fn (PaymentMethod $record): string => "Delete: {$record->name}?")
                    ->modalDescription('Are you sure you want to delete this payment method? This action cannot be undone.')
                    ->action(function (PaymentMethod $record): void {
                        $itemName = $record->item?->name ?? 'this item';

                        $record->delete();

                        Notification::make()
                            ->title('Payment Method deleted successfully.')
                            ->body('Payment Method record for "' . $itemName . '" was deleted.')
                            ->success()
                            ->send();
                    }),
                Action::make('edit')
                    ->label('')->label('')
                    ->iconButton()
                    ->tooltip('Edit Payment Method')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (PaymentMethod $record): string => route('payment.method.edit', $record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.management.list-payment-methods');
    }
}
