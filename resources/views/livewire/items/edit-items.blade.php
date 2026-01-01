<div>
    <form wire:submit="save">
        {{ $this->form }}

        <x-filament::button
            type="submit"
            class="mt-4"
            color="success"
            icon="heroicon-s-check-circle"
            icon-position="before">
                Save Changes
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>
