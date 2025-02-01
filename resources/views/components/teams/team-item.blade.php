<div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between">
        <span class="inline-block">{{$team->name}}</span>
        <span class="block"
              x-data=""
              x-on:click.prevent="$dispatch('open-modal', 'join-team-{{$team->id}}')">
               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6 cursor-pointer transition ease-in hover:text-green-600">
  <path stroke-linecap="round" stroke-linejoin="round"
        d="m12.75 15 3-3m0 0-3-3m3 3h-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
</svg>

            </span>
    </div>
</div>


<x-modal name="join-team-{{$team->id}}" focusable>
    <form method="post" action="{{route('team.join')}}" class="p-6">
        @csrf
        @method('POST')

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Do you want to join team named: ') . $team->name }}
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

            <x-primary-button class="ms-3">
                {{ __('Join the team') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
