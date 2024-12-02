<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Get Token
            </h2>
            <a class="flex-shrink-0 bg-yellow-500 hover:bg-yellow-700 border-yellow-500 text-white text-sm py-1 px-2 rounded" 
            type="button" 
            href="{{ route('dashboard') }}">
                Kembali
            </a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="relative overflow-x-auto mt-6">
                        <form class="px-10 w-full max-w-full" action="{{route('dashboard.get-token.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex flex-wrap -mx-3 mb-3">
                                <div class="w-full px-3">
                                    <label class="block uppercase tracking-wide text-white-700 text-xs font-bold mb-5" for="grid-url-sandbox">
                                        Daftar Bank
                                    </label>
                                    <select class="bg-gray-700 w-full px-4 rounded-4xl" name="kode_bank">
                                        <option value="" disabled>-- Pilih Nama Bank --</option>
                                        @foreach($data as $d)
                                            <option value="{{$d->kode_bank}}">{{$d->kode_bank}} - {{$d->nama_bank}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex py-2">
                                <button class="flex-shrink-0 bg-blue-500 hover:bg-blue-700 border-blue-500 hover:border-blue-700 text-sm border-4 text-white py-1 px-2 rounded" type="submit">
                                    Get Token
                                </button>
                                <a class="flex-shrink-0 border-transparent border-4 text-white-500 hover:text-blue-500 text-sm py-1 px-2 rounded" type="button" href="{{route('dashboard')}}">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
