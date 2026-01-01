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

class CreatePaymentMethod extends Component implements HasActions, HasSchemas
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
                Section::make('New Payment Method')
                    ->description('Fill in the payment method details below.')
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
            ->model(PaymentMethod::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = PaymentMethod::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()
            ->title('Payment Method Created')
            ->body("The payment method has been successfully created.")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.management.create-payment-method');
    }
}
