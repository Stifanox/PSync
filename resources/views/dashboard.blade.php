<x-app-layout>
    <x-slot name="header">
        <span class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight block">
            {{ __('Dashboard') }}
        </h2>
        <span class="inline-flex text-gray-800 dark:text-gray-200 cursor-pointer"
              x-data=""
              x-on:click.prevent="$dispatch('open-modal', 'add-team')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add new team
        </span>
        </span>
    </x-slot>

    <x-modal name="add-team" :show="$errors->teamCreate->isNotEmpty()" focusable>
        <form method="post" action="{{ route('createTeam') }}" class="p-6">
            @csrf
            @method('POST')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Create new team') }}
            </h2>

            <div class="mt-6">
                <x-input-label for="teamName" value="{{ __('Team name') }}" class="sr-only"/>

                <x-text-input
                        id="password"
                        name="teamName"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Team name') }}"
                />

                <x-input-error :messages="array_map(function(string $error){
                            return match ($error){
                              'validation.string' => 'Provided input must be string',
                              'validation.required' => 'Input is required',
                            };
                            }, $errors->teamCreate->get('teamName'))" class="mt-2"/>
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Add new team') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h3 class="font-medium text-base text-gray-800 dark:text-gray-200 leading-tight block">{{__('Your teams')}}</h3>
            @each('components.dashboard.team-item', $teams, 'team')
        </div>
    </div>
</x-app-layout>
