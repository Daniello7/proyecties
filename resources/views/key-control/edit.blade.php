<div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-3xl gradient-border shadow-xl w-full max-w-4xl">
        <x-header :content="__('Key Control').' - '.__('Edit Exit')"/>
        @include('key-control.edit-form-fields')
        <x-primary-button wire:click="updateKeyControl({{ $key_id }})" class="m-4">
            {{ __('Update') }}
            <x-svg.confirm-icon class="w-7 h-7 ml-2"/>
        </x-primary-button>
        <x-secondary-button wire:click="closeModal" class="m-4">
            {{ __('Cancel') }}
            <x-svg.cancel-icon class="w-7 h-7 ml-2"/>
        </x-secondary-button>
    </div>
</div>
