<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bank Information') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="w-full my-2">
                        <x-button-modal modalName='addBankInfo'>Add Bank Info</x-button-modal>
                    </div>
                    @include('bank-info.create')
                    @include('bank-info.edit')
                    <div class="relative overflow-x-auto mt-6">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="search-table">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">PARTNER ID</th>
                                    <th scope="col" class="px-6 py-3">CLIENT ID</th>
                                    <th scope="col" class="px-6 py-3">CLIENT SECRET</th>
                                    <th scope="col" class="px-6 py-3">PUBLIC KEY</th>
                                    <th scope="col" class="px-6 py-3">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bankInfo as $d)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ Str::upper($d->partner_id) }}
                                    </th>
                                    <td class="px-6 py-4">{{ $d->client_id }}</td>
                                    <td class="px-6 py-4">{{ $d->client_secret }}</td>
                                    <td class="px-6 py-4">{{ Str::substr($d->rsa_public_key, 0, 64) }} ........</td>
                                    <td class="px-6 py-4">
                                        <div class="inline-flex">
                                            <button @click="openEditModal({{ $d }})" class="m-2 px-4 py-2 bg-blue-600 text-white rounded-md">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <form x-data @submit.prevent="confirmDelete($event)" action="{{ route('bank-info.destroy', $d->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button type="submit" class="m-2"><i class="fa fa-trash-can"></i></x-danger-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
        //         const dataTable = new simpleDatatables.DataTable("#search-table", {
        //             searchable: true,
        //             sortable: false
        //         });
        //     }
        // });

        function confirmSubmission(event) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to submit this form?",
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

        function confirmDelete(event) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this item?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
        }

        function openEditModal(bankInfo) {
            console.log(bankInfo);
            const modal = document.querySelector('[x-data="{ bankInfo: {} }"]');
            if (modal) {
                modal.__x.$data.bankInfo = bankInfo;
                modal.__x.$dispatch('open-modal', 'editBankInfo');
            } else {
                console.error('Modal not found');
            }
        }
    </script>
</x-app-layout>
