<?php

namespace App\Livewire\Management;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use App\Models\User;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Component implements HasActions, HasSchemas
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
                Section::make('New User')
                    ->description('Fill in the user details below.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->unique(ignoreRecord: true)
                            ->required(),
                        Select::make('role')
                            ->label('Role')
                            ->options([
                                'admin' => 'Admin',
                                'cashier' => 'Cashier',
                                'manager' => 'Manager',
                                'staff' => 'Staff',
                            ])
                            ->native(false)
                            ->required(),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->minLength(8)
                            ->revealable()
                            ->required()
                            ->unique(ignoreRecord: true),
                    ])
            ])
            ->statePath('data')
            ->model(User::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = User::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()
            ->title('User Created')
            ->body("The user has been successfully created.")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.management.create-user');
    }
}
