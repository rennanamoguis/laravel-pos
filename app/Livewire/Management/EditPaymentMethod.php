<?php

namespace App\Livewire\Management;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use App\Models\PaymentMethod;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextArea;
use Filament\Schemas\Components\Section;
use Filament\Notifications\Notification;

class EditPaymentMethod extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public PaymentMethod $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit Payment Method')
                    ->description('Update the payment method details below.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Payment Method')
                            ->required(),
                        TextArea::make('description')
                             ->label('Description')
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
            ->title('Payment Method Updated')
            ->body("The payment method {$this->record->name} has been successfully updated.")
            ->success()
            ->send();

    }

    public function render(): View
    {
        return view('livewire.management.edit-payment-method');
    }
}
