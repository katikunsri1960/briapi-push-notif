<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bank Information') }} / Edit / {{ Str::upper($data->partner_id) }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form x-data @submit.prevent="confirmSubmission($event)" action="{{ route('bank-info.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="grid grid-cols-2 gap-6 mt-4 sm:grid-cols-3">
                            <div>
                                <x-input-label for="partner_id" :value="__('Partner ID')" />
                                <x-text-input id="partner_id" class="block mt-1 w-full" type="text" name="partner_id" value="{{ $data->partner_id }}" required autofocus />
                            </div>
                            <div>
                                <x-input-label for="client_id" :value="__('Client ID')" />
                                <x-text-input id="client_id" class="block mt-1 w-full" type="text" name="client_id" value="{{ $data->client_id }}" required />
                            </div>
                            <div>
                                <x-input-label for="client_secret" :value="__('Client Secret')" />
                                <x-text-input id="client_secret" class="block mt-1 w-full" type="text" name="client_secret" value="{{ $data->client_secret }}" required />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-1">
                            <div>
                                <x-input-label for="rsa_public_key" :value="__('Public Key')" />
                                <textarea name="rsa_public_key" id="rsa_public_key" rows="10" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{$data->rsa_public_key}}</textarea>
                            </div>
                        </div>
                        {{-- {{ $data->rsa_public_key }} --}}
                        <div class="flex items center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Update') }}
                            </x-primary-button>

                            <x-link-button :href="route('bank-info')" class="ml-4">
                                {{ __('Cancel') }}
                            </x-link-button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

</x-app-layout>
