<div class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 transition-colors">
    <x-header :content="__('Key Control').' - '.__('New Exit')"/>
    <livewire:key-control.key-select :is-form="true"/>
    <x-primary-button class="m-4">
        {{ __('Save') }}
        <x-svg.confirm-icon class="w-7 h-7 ml-2"/>
    </x-primary-button>
</div>
