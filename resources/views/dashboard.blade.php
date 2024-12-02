<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div id="alertBox" class="pt-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between items-center">
                    <span>{{ __("You're logged in!") }}</span>
                    <button 
                        onclick="closeAlert()" 
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="pt-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="w-full mb-5">
                        <h1 class="text-3xl">Setting Parameter WS</h1>
                    </div>
                    <div class="w-full my-3 text-end">
                        <a class="bg-teal-600 hover:bg-teal-900 text-white font-bold py-2 px-4 border border-teal-700 rounded" type="button" href="{{route('dashboard.get-token')}}"> Get Token</a>
                        <a class="bg-blue-600 hover:bg-blue-900 text-white font-bold py-2 px-4 border border-blue-700 rounded" type="button" href="{{route('dashboard.tambah-bank')}}"> Tambah Bank</a>
                    </div>

                    <div class="relative overflow-x-auto mt-6">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-3 py-3 text-center">
                                        NO
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        ACTION
                                    </th>
                                    <th scope="col" class="px-3 py-3 text-center">
                                        KODE BANK
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        NAMA BANK
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        URL API BANK
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        CLIENT ID
                                    </th>
                                    <th scope="col" class="px-3 py-3 text-center">
                                        COORPORATE CODE
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        RSA PRIVATE KEY
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $d)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td scope="row"
                                            class="px-3 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                                            {{ $data->firstItem() + $key }}
                                        </td>
                                        <td class="px-25 py-4 text-center">
                                            <div class="row my-3">
                                                <a class="bg-blue-600 hover:bg-blue-900 text-white text-xs font-bold py-1 px-1 border border-blue-700 rounded" type="button" href="{{route('dashboard.create-va', ['id_bank' => $d->id])}}">Create VA</a>
                                            </div>
                                            <div class="row">
                                                <a class="bg-red-600 hover:bg-red-900 text-white text-xs font-bold py-1 px-1 border border-red-700 rounded" type="button" href="{{route('dashboard.tambah-bank')}}">Delete VA</a>
                                            </div>
                                            <div class="row my-3">
                                                <a class="bg-blue-600 hover:bg-blue-900 text-white text-xs font-bold py-1 px-1 border border-blue-700 rounded" type="button" href="{{route('dashboard.tambah-bank')}}">Edit Data</a>
                                            </div>
                                            <div class="row">
                                                <a class="bg-red-600 hover:bg-red-900 text-white text-xs font-bold py-1 px-1 border border-red-700 rounded" type="button" href="{{route('dashboard.tambah-bank')}}">Delete Data</a>
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 text-center">
                                            {{$d->kode_bank}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$d->nama_bank}}
                                        </td>
                                        <td class="px-6 py-4">
                                            <ul>
                                                <li>Sandbox : {{$d->url_api_sandbox}}</li>
                                                <li>Production : {{$d->url_api_production}}</li>
                                            </ul>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            {{$d->client_key}}
                                        </td>
                                        <td class="px-3 py-4 text-center">
                                            {{$d->partner_service_id}}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            {{ \Illuminate\Support\Str::limit($d->private_key, 50, '...') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="my-5">
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    function closeAlert() {
        const alertBox = document.getElementById('alertBox');
        alertBox.style.display = 'none';
    }
</script>