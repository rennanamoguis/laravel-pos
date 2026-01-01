<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;

class EditCustomer extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Customer $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit Customer Information')
                    ->description('Update the customer details below.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Customer Name')
                            ->required(),
                        TextInput::make('email')
                             ->label('Email Address')
                             ->email()
                             ->unique(),
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel(),
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
            ->title('Customer Updated')
            ->body("The customer {$this->record->name} has been successfully updated.")
            ->success()
            ->send();

    }

    public function render(): View
    {
        return view('livewire.customer.edit-customer');
    }
}
