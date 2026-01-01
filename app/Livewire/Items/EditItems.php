<?php

namespace App\Livewire\Items;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use App\Models\Item;
use Livewire\Component;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class EditItems extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Item $record;

    public ?array $data = [];

    public function mount(): void
    {
        //It populates the form with the existing record data
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Define your form components here
                Section::make('Edit the Item')
                    ->description('Update the item details below.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Item Name')
                            ->required(),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->unique()
                            ->required(),
                        TextInput::make('price')
                            ->label('Price')
                            ->prefix('₱')
                            ->numeric()
                            ->inputMode('decimal')
                            ->required(),
                        ToggleButtons::make('status')
                        ->label('Is this item active?')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                        ])
                        ->grouped()
                        ->colors([
                            'active' => 'success',
                            'inactive' => 'warning',
                        ])
                    ])
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);

        Notification::make()
            ->title('Item Updated')
            ->body("The item {$this->record->name} has been successfully updated.")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.items.edit-items');
    }
}
