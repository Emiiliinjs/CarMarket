<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Dzēst kontu') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Pēc konta dzēšanas visi tā resursi un dati tiks neatgriezeniski dzēsti. Pirms dzēšanas lejupielādē visus datus, kurus vēlies paturēt.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Dzēst kontu') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Vai tiešām vēlies dzēst kontu?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Pēc konta dzēšanas visi tava konta resursi un dati tiks neatgriezeniski dzēsti. Ievadi paroli, lai apstiprinātu neatgriezenisku dzēšanu.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Parole') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Parole') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Atcelt') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Dzēst kontu') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
