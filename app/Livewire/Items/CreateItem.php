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

class CreateItem extends Component implements HasActions, HasSchemas
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
                Section::make('New Item')
                    ->description('Fill in the item details below.')
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
                        ->default('active'),
                    ])
            ])
            ->statePath('data')
            ->model(Item::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = Item::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()
            ->title('Item Created')
            ->body("The item has been successfully created.")
            ->success()
            ->send();

    }

    public function render(): View
    {
        return view('livewire.items.create-item');
    }
}
