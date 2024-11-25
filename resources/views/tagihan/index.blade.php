<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tagihan UKT Mahasiswa Universitas Sriwijaya
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="w-full my-9">
                        <x-link-button >
                            Tarik Data Tagihan
                        </x-link-button>
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
                                        NIM
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        NAMA MAHASISWA
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        NAMA PROGRAM STUDI
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        TIPE UKT
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        TAGIHAN
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        STATUS
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
                                        {{$d->nim}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$d->nama_mahasiswa}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$d->nama_program_studi}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$d->tipe_ukt}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$d->tagihan}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$d->status}}
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
