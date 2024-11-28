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
                    <div class="w-full my-3">
                        @if($semester)
                            <a 
                                class="bg-blue-600 hover:bg-blue-900 text-white font-bold py-2 px-4 border border-blue-700 rounded" 
                                href="{{ route('tagihan.get-tagihan', ['semester' => $semester->kode_periode]) }}"
                            >
                                Tarik Data Tagihan
                            </a>
                        @endif

                    </div>

                    <div class="relative overflow-x-auto mt-6">
                        <table id="dt" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 text-center">
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
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                                        {{$row = $row+1}}
                                    </th>
                                    <td class="px-6 py-4 text-center">
                                        {{$d->nim}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$d->nama_mahasiswa}}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        {{$d->nama_jenjang_didik}} - {{$d->nama_program_studi}}
                                    </td>
                                    <td class="px-20 py-4 text-center">
                                        {{$d->tipe_ukt}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$d->tagihan}}
                                    </td>
                                    <td class="px-15 py-4 text-center">
                                        @if ($d->status_tagihan != 0)
                                            <span class="inline-block bg-blue-500 rounded-lg text-white text-xs py-2 px-2">Lunas</span>
                                        @else
                                            <span class="inline-block bg-red-500 rounded-lg text-white text-xs py-2 px-2">Belum Bayar</span>
                                        @endif
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
