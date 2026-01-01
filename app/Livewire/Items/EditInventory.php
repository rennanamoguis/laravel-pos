<?php

namespace App\Livewire\Items;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use App\Models\Inventory;
use Livewire\Component;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;

class EditInventory extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Inventory $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit Inventory')
                    ->description('Update the inventory details below.')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('item_name')
                            ->label('Item Name')
                            ->content(fn ($record) => $record?->item?->name ?? '—')
                            ->color('primary'),
                        TextInput::make('quantity')
                             ->label('Quantity')
                             ->numeric()
                             ->required(),
                    ]),
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);

        Notification::make()
            ->title('Inventory Updated')
            ->body("The inventory {$this->record->item->name} has been successfully updated.")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.items.edit-inventory');
    }
}
