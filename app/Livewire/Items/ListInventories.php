<?php

namespace App\Livewire\Items;

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
use App\Models\Inventory;
use Livewire\Component;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class ListInventories extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Inventory::query())
            ->columns([
                TextColumn::make('item.name')->label('Item Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')->label('Quantity')
                    ->badge()
                    ->color(fn (?int $state): string => match(true){
                        ($state ?? 0) <= 20 => 'danger',
                        ($state ?? 0) <= 50 => 'warning',
                        default => 'success',
                    }),
                TextColumn::make('created_at')->label('Date Updated')
                    ->timezone('Asia/Manila')
                    ->dateTime('M d, Y, H:i a')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->label('New Inventory')
                    ->url(fn (): string => route('inventory.create')),
            ])
            ->recordActions([
                // Action::make('delete')
                //     ->label('')
                //     ->iconButton()
                //     ->tooltip('Delete Inventory')
                //     ->icon('heroicon-o-trash')
                //     ->color('danger')
                //     ->action(fn (Inventory $record): mixed => $record->delete())
                //     ->successNotification(
                //         Notification::make()
                //             ->title('Inventory deleted successfully.')
                //             ->success()
                //     )

                Action::make('delete')
                    ->label('')              // no "Delete" text
                    ->iconButton()           // render as icon-only button
                    ->tooltip('Delete')      // optional: still show hint on hover
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Inventory $record): string => "Delete: {$record->item?->name}?")
                    ->modalDescription('Are you sure you want to delete this inventory? This action cannot be undone.')
                    ->action(function (Inventory $record): void {
                        $itemName = $record->item?->name ?? 'this item';

                        $record->delete();

                        Notification::make()
                            ->title('Inventory deleted successfully.')
                            ->body('Inventory record for "' . $itemName . '" was deleted.')
                            ->success()
                            ->send();
                    }),

                    Action::make('edit')
                        ->label('')
                        ->iconButton()
                        ->tooltip('Edit Inventory')
                        ->icon('heroicon-o-pencil-square')
                        ->url(fn (Inventory $record): string => route('inventory.edit', $record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.items.list-inventories');
    }
}
