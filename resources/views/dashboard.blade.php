<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="w-full mb-5">
                        <h1 class="text-3xl">Setting Parameter WS</h1>
                    </div>
                    <div class="w-full my-3 text-end">
                        <a class="bg-blue-600 hover:bg-blue-900 text-white font-bold py-2 px-4 border border-blue-700 rounded"> + | Tambah Bank</a>
                    </div>

                    <div class="relative overflow-x-auto mt-6">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        NO
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        KODE BANK
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        NAMA BANK
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        CLIENT ID
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        COORPORATE CODE
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        RSA PRIVATE KEY
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        TOKEN
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        ACTION
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $row = 0;
                                @endphp
                                @foreach ($data as $d)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$row = $row+1}}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{$d->kode_bank}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$d->nama_bank}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$d->client_key}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$d->partner_service_id}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$d->private_key}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$d->token}}
                                    </td>
                                    <td class="px-6 py-4">
                                        
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
</x-app-layout>
