<x-modal name="addBankInfo" :show="false" maxWidth="2xl">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-5">
            Tambah Bank Info
        </h2>
        <hr>
        <div class="my-3">
            <form action="{{route('bank-info.store')}}" method="post">
                @csrf
                <div>
                    <x-input-label for="name" :value="__('PARTNER ID')" />
                    <x-text-input id="partner_id" class="block mt-1 w-full" type="text" name="partner_id"
                        :value="old('partner_id')" required autofocus autocomplete="partner_id" />
                    <x-input-error :messages="$errors->get('partner_id')" class="mt-2" />
                </div>
                <div class="mt-6 flex justify-end">
                    <x-primary-button class="m-2">
                        {{ __('Save') }}
                    </x-primary-button>
                    <x-secondary-button class="m-2" x-on:click="$dispatch('close-modal', 'addBankInfo')">
                        {{ __('Close') }}
                    </x-secondary-button>

                </div>
            </form>
        </div>

    </div>
</x-modal>
