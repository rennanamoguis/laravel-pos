<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Livewire\Management\ListUsers;
use App\Livewire\Management\CreateUser;
use App\Livewire\Management\EditUsers;
use App\Livewire\Items\ListItems;
use App\Livewire\Items\EditItems;
use App\Livewire\Items\CreateItem;
use App\Livewire\Customer\EditCustomer;
use App\Livewire\Items\ListInventories;
use App\Livewire\Items\EditInventory;
use App\Livewire\Items\CreateInventory;
use App\Livewire\Sales\ListSales;
use App\Livewire\Customer\ListCustomers;
use App\Livewire\Customer\CreateCustomer;
use App\Livewire\Management\ListPaymentMethods;
use App\Livewire\Management\CreatePaymentMethod;
use App\Livewire\Management\EditPaymentMethod;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/manage-users', ListUsers::class)->name('users.index');
    Route::get('/create-user', CreateUser::class)->name('user.create');
    Route::get('/edit-users/{record}', EditUsers::class)->name('user.edit');
    
    Route::get('/manage-items', ListItems::class)->name('items.index');
    Route::get('/create-item', CreateItem::class)->name('item.create');
    Route::get('/edit-items/{record}', EditItems::class)->name('item.edit');
    
    Route::get('/manage-inventories', ListInventories::class)->name('inventories.index');
    Route::get('/create-inventory', CreateInventory::class)->name('inventory.create');
    Route::get('/edit-inventory/{record}', EditInventory::class)->name('inventory.edit');
    
    Route::get('/manage-sales', ListSales::class)->name('sales.index');
    
    Route::get('/manage-customers', ListCustomers::class)->name('customers.index');
    Route::get('/create-customer', CreateCustomer::class)->name('customer.create');
    Route::get('/edit-customers/{record}', EditCustomer::class)->name('customer.edit');

    Route::get('/manage-payment-methods', ListPaymentMethods::class)->name('payment.method.index');
    Route::get('/create-payment-methods', CreatePaymentMethod::class)->name('payment.method.create');
    Route::get('/edit-payment-methods/{record}', EditPaymentMethod::class)->name('payment.method.edit');
});

