<x-home-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Control Access') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-entry-table
                            :model="\App\Models\PersonEntry::class"
                            :columns="['person_id','contact_user_id','reason','comment_id']"
                            :relations="[
                                'person' => ['name','last_name'],
                                'user' => 'name',
                                'comment' => 'body'
                            ]"
                            :alias="[__('Name'),__('Contact'),__('Reason'),__('Comment')]"
                    />
                </div>
            </div>
        </div>
    </div>
</x-home-layout>
