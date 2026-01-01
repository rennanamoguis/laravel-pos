<?php

namespace App\Livewire\Management;

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
use Livewire\Component;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class ListUsers extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => User::query())
            ->columns([
                TextColumn::make('name')->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')->label('Email')
                    ->searchable(),
                TextColumn::make('role')->label('Role')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->label('New User')
                    ->url(fn (): string => route('user.create')),
            ])
            ->recordActions([
                Action::make('delete')
                    ->label('')              // no "Delete" text
                    ->iconButton()           // render as icon-only button
                    ->tooltip('Delete')      // optional: still show hint on hover
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading(fn (User $record): string => "Delete: {$record->name}?")
                    ->modalDescription('Are you sure you want to delete this user? This action cannot be undone.')
                    ->action(function (User $record): void {
                        $itemName = $record->name ?? 'this user';

                        $record->delete();

                        Notification::make()
                            ->title('User deleted successfully.')
                            ->body('User record for "' . $itemName . '" was deleted.')
                            ->success()
                            ->send();
                    }),
                Action::make('edit')
                    ->label('')
                    ->iconButton()
                    ->tooltip('Edit User')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (User $record): string => route('user.edit', $record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.management.list-users');
    }
}
