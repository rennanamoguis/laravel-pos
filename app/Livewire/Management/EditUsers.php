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


class EditUsers extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public User $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit User')
                    ->description('Update the user details below.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->unique(ignoreRecord: true)
                            ->disabled()
                            ->required(),
                        Select::make('role')
                            ->label('Role')
                            ->options([
                                'admin' => 'Admin',
                                'cashier' => 'Cashier',
                                'manager' => 'Manager',
                                'staff' => 'Staff',
                            ])
                            ->native(false),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->minLength(8)
                            ->revealable()
                            ->helperText('Leave blank if no changes.')
                            ->dehydrated(fn (?string $state): bool => filled($state)) // only save if not empty
                            ->dehydrateStateUsing(fn (?string $state) => filled($state) ? Hash::make($state) : null),
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
            ->title('User Updated')
            ->body("The user {$this->record->name} has been successfully updated.")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.management.edit-users');
    }
}
