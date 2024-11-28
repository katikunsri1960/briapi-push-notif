<x-modal name="addBankInfo" :show="false" maxWidth="2xl">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-5">
            Tambah Bank Info
        </h2>
        <hr>
        <div class="my-3">
            <form x-data @submit.prevent="confirmSubmission($event)" action="{{ route('bank-info.store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <x-input-label for="name" :value="__('PARTNER ID')" />
                    <x-text-input id="partner_id" class="block mt-1 w-full" type="text" name="partner_id"
                        :value="old('partner_id')" required autofocus autocomplete="partner_id" />
                    <x-input-error :messages="$errors->get('partner_id')" class="mt-2" />
                </div>
                <div class="mb-3">
                    <x-input-label for="name" :value="__('CLIENT ID')" />
                    <x-text-input id="client_id" class="block mt-1 w-full" type="text" name="client_id"
                        :value="old('client_id')" required autofocus autocomplete="client_id" />
                        <x-primary-button type="button" class="my-2" x-on:click="document.getElementById('client_id').value = [...Array(32)].map(() => Math.random().toString(36)[2]).join('')">
                            {{ __('Generate') }}
                        </x-primary-button>
                    <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                </div>
                <div class="mb-3">
                    <x-input-label for="name" :value="__('CLIENT SECRET')" />
                    <x-text-input id="client_secret" class="block mt-1 w-full" type="text" name="client_secret"
                        :value="old('client_secret')" required autofocus autocomplete="client_secret" />
                    {{-- <x-primary-button>tes</x-primary-button> --}}
                    <x-primary-button type="button" class="my-2" x-on:click="document.getElementById('client_secret').value = [...Array(16)].map(() => Math.random().toString(36)[2]).join('')">
                        {{ __('Generate') }}
                    </x-primary-button>
                    <x-input-error :messages="$errors->get('client_secret')" class="mt-2" />
                </div>
                <div class="mb-3">
                    <x-input-label for="name" :value="__('PUBLIC KEY')" />
                    <x-textarea-input id="rsa_public_key" class="block mt-1 w-full" type="text" name="rsa_public_key"
                        :value="old('rsa_public_key')" required autofocus autocomplete="rsa_public_key" />
                    <x-input-error :messages="$errors->get('rsa_public_key')" class="mt-2" />
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
<script>
    function confirmSubmission(event) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to submit the form?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit();
            }
        });
    }
</script>
