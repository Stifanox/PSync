<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight block">
            {{ __('Users in organization') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="font-medium text-base text-gray-800 dark:text-gray-200 leading-tight block">{{__('Your teams')}}</h3>
            @each('components.users.user-item', $users, 'user')
        </div>
    </div>

</x-app-layout>
