<!-- resources/views/components/edit-modal.blade.php -->
<x-modal name="editBankInfo" :show="false" maxWidth="2xl">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-5">
            Edit Bank Info
        </h2>
        <hr>
        <div class="my-3">
            <form x-data="{ bankInfo: {} }" @submit.prevent="submitEditForm" action="{{ route('bank-info.update', '') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" x-model="bankInfo.id">
                <div class="mb-3">
                    <x-input-label for="edit_partner_id" :value="__('PARTNER ID')" />
                    <x-text-input id="edit_partner_id" class="block mt-1 w-full" type="text" name="partner_id" x-model="bankInfo.partner_id" required autofocus autocomplete="partner_id" />
                    <x-input-error :messages="$errors->get('partner_id')" class="mt-2" />
                </div>
                <div class="mb-3">
                    <x-input-label for="edit_client_id" :value="__('CLIENT ID')" />
                    <x-text-input id="edit_client_id" class="block mt-1 w-full" type="text" name="client_id" x-model="bankInfo.client_id" required autofocus autocomplete="client_id" />
                    <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                </div>
                <div class="mb-3">
                    <x-input-label for="edit_client_secret" :value="__('CLIENT SECRET')" />
                    <x-text-input id="edit_client_secret" class="block mt-1 w-full" type="text" name="client_secret" x-model="bankInfo.client_secret" required autofocus autocomplete="client_secret" />
                    <x-input-error :messages="$errors->get('client_secret')" class="mt-2" />
                </div>
                <div class="mb-3">
                    <x-input-label for="edit_rsa_public_key" :value="__('PUBLIC KEY')" />
                    <x-textarea-input id="edit_rsa_public_key" class="block mt-1 w-full" name="rsa_public_key" x-model="bankInfo.rsa_public_key" required autofocus autocomplete="rsa_public_key" />
                    <x-input-error :messages="$errors->get('rsa_public_key')" class="mt-2" />
                </div>
                <div class="mt-6 flex justify-end">
                    <x-primary-button class="m-2">
                        {{ __('Save') }}
                    </x-primary-button>
                    <x-secondary-button class="m-2" x-on:click="$dispatch('close-modal', 'editBankInfo')">
                        {{ __('Close') }}
                    </x-secondary-button>
                </div>
            </form>
        </div>
    </div>
</x-modal>

<script>
    function submitEditForm(event) {
        event.target.submit();
    }
</script>
