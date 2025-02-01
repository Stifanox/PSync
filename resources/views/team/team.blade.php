<x-app-layout>
    <div class="flex h-screen">

        <!-- Sidebar -->
        <div class="md:w-1/5 bg-gray-300 p-4 flex-col gap-4 hidden md:flex dark:bg-gray-900">
            @foreach ($team->users as $user)
                <div class="text-white p-2 border rounded-lg border-gray-700 hover:bg-gray-700 cursor-pointer flex items-center">
                    <div class="bg-gray-600 p-2 mr-3 rounded-full w-max">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512"
                             height="20px" width="20px" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"></path>
                        </svg>
                    </div>
                    <span class="block">{{ $user->name }}</span>
                </div>
            @endforeach
        </div>

        <!-- Main content -->
        <div class="w-full md:w-4/5 bg-gray-200 dark:bg-gray-800 flex flex-col justify-center items-center overflow-hidden relative">
            <div class="absolute top-10 right-6 cursor-pointer"
                 x-data=""
                 x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-6 text-gray-800 dark:text-gray-200">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
            </div>
            <div id="clock-reset" class="p-4 text-2xl text-gray-800 dark:text-gray-200">

            </div>
            <div id="clock-state" class="p-4 text-2xl text-gray-800 dark:text-gray-200">
            </div>
            <div id="clock" class="text-red-500 border border-red-500 p-4 text-2xl">
                00:00:00
            </div>
            <button class="mt-4 text-purple-500 border border-purple-500 p-2 reset-button">Reset</button>
        </div>
    </div>
    @vite('resources/js/teamClock.js')

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-6 ml-6">
            {{ __('Team Configuration') }}
        </h2>
        <h3 class="text-lg text-gray-900 dark:text-gray-100 mt-6 ml-6">
            {{ __('Note that changes will be noticed when clock is reset or when new clock is started') }}
        </h3>
        <form method="post" action="{{ route('team.config.update', ['teamId'=> $team->id]) }}" class="p-6">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="work-time" :value="__('Work time (in minutes)')"/>
                <x-text-input id="work-time" name="workTime" type="text" class="mt-1 block w-full"
                              :value="$team->teamConfiguration->work_time" required autofocus/>
                <x-input-error class="mt-2" :messages="$errors->teamConfig->get('workTime')"/>
            </div>
            <div class="mt-3">
                <x-input-label for="free-time" :value="__('Free time (in minutes)')"/>
                <x-text-input id="free-time" name="freeTime" type="text" class="mt-1 block w-full"
                              :value="$team->teamConfiguration->free_time" required autofocus/>
                <x-input-error class="mt-2" :messages="$errors->teamConfig->get('freeTime')"/>
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Save') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
