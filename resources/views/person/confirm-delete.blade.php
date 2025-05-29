<div class="fixed left-0 top-0 z-50 w-full h-screen flex justify-center items-center bg-black bg-opacity-30">
    <div class="gradient-border p-8 flex flex-col justify-center items-center gap-4 rounded-3xl shadow-2xl text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800">
        <p class="custom-gradient-text font-bold">{{ __('Are you sure you want to delete the key?') }}</p>
        <div class="w-full flex justify-evenly">
            <x-primary-button wire:click="deletePerson">
                {{ __('Accept') }}
                <x-svg.confirm-icon class="w-6 h-6"/>
            </x-primary-button>
            <x-secondary-button wire:click="closeModal()">
                {{ __('Cancel') }}
                <x-svg.cancel-icon class="w-6 h-6"/>
            </x-secondary-button>
        </div>
    </div>
</div>