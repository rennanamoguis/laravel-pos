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
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class CreateInventory extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('New Inventory')
                ->description('Fill in the inventory details below.')
                ->columns(2)
                ->schema([
                    Select::make('item_id')
                            ->relationship('item','name')
                            ->searchable()
                            ->preload()
                            ->native(false),
                    TextInput::make('quantity')
                    ->numeric(),
                ])
            ])
            ->statePath('data')
            ->model(Inventory::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = Inventory::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()
            ->title('Inventory Created')
            ->body("The inventory has been successfully created.")
            ->success()
            ->send();

    }

    public function render(): View
    {
        return view('livewire.items.create-inventory');
    }
}
