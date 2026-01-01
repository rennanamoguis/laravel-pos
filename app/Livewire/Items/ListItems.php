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
use App\Models\Item;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;


class ListItems extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Item::query())
            ->columns([
                TextColumn::make('name')->label('Item Name')->searchable()->sortable(),
                TextColumn::make('sku')->label('SKU')->searchable(),
                TextColumn::make('price')->label('Price')->money('php', true)->sortable(),
                TextColumn::make('status')->label('Status')->badge()->color(fn ($state) => $state === 'active' ? 'success' : 'warning'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Add New Item')
                    ->url(fn (): string => route('item.create')),
            ])
            ->recordActions([
                // Action::make('edit')
                //     ->label('Edit')
                //     ->url(fn (Item $record): string => route('items.edit', $record))
                //     ->icon('heroicon-o-pencil'),

                // Action::make('delete')
                //     ->action(fn (Item $record) => $record->delete())
                //     ->icon('heroicon-o-trash')
                //     ->color('danger')
                //     ->requiresConfirmation()
                //     ->successNotification(Notification::make()
                //         ->title('Item Deleted')
                //         ->body('The item has been successfully deleted.')
                //         ->success()),

                Action::make('delete')
                    ->label('')
                    ->iconButton()
                    ->tooltip('Delete Item')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Item $record): string => "Delete Item: {$record->name}")
                    ->modalDescription('Are you sure you want to delete this item? This action cannot be undone.')
                    ->action(function (Item $record): void{
                        $name = $record->name;
                        $record->delete();
                    
                         Notification::make()
                            ->title('Item Deleted')
                            ->body("{$name} has been successfully deleted.")
                            ->success()
                            ->send();
                }),

                Action::make('edit')
                    ->label('')
                    ->iconButton()
                    ->tooltip('Edit Item')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (Item $record): string => route('item.edit', $record)),


            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.items.list-items');
    }
}
