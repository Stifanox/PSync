<a href="teams/{{$team->id}}">

    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between">
            <span class="inline-block">{{$team->name}}</span>
            <span class="block"
                  x-data=""
                  x-on:click.prevent="$dispatch('open-modal', 'remove-team-{{$team->id}}')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-6 cursor-pointer transition ease-in hover:text-red-600">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                </svg>
            </span>
        </div>
    </div>
</a>

<x-modal name="remove-team-{{$team->id}}" focusable>
    <form method="post" action="{{route('team.delete')}}" class="p-6">
        @csrf
        @method('DELETE')

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Are you sure you want to delete team of name: ') . $team->name }}
        </h2>

        <x-text-input
            id="team-id"
            name="teamId"
            type="text"
            class="hidden"
            value="{{$team->id}}"/>


        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3">
                {{ __('Delete team') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>

