<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tambah Data Bank Virtual Account
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="relative overflow-x-auto mt-6">
                        <form class="px-10 w-full max-w-full" action="{{route('dashboard.tambah-bank.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex flex-wrap -mx-3 mb-6">
                                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                    <label class="block uppercase tracking-wide text-white-700 text-xs font-bold mb-2" for="grid-kode-bank">
                                        Kode Bank
                                    </label>
                                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid-kode-bank" name="kode_bank" type="text" placeholder="Masukkan Kode Bank">
                                    </div>
                                    <div class="w-full md:w-1/2 px-3">
                                    <label class="block uppercase tracking-wide text-white-700 text-xs font-bold mb-2" for="grid-nama-bank">
                                        Nama Bank
                                    </label>
                                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-nama-bank" name="nama_bank" type="text" placeholder="Masukkan Nama Bank">
                                </div>
                            </div>
                            <div class="flex flex-wrap -mx-3 mb-6">
                                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                                    <label class="block uppercase tracking-wide text-white-700 text-xs font-bold mb-2" for="grid-coorporate-code">
                                        Coorporate Code
                                    </label>
                                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-coorporate-code" name="coorporate_code" type="text" placeholder="Masukkan Coorporate Code">
                                </div>
                                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                                    <label class="block uppercase tracking-wide text-white-700 text-xs font-bold mb-2" for="grid-client-id">
                                        Client ID
                                    </label>
                                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-client-id" name="client_id" type="text" placeholder="Masukkan Client ID">
                                </div>
                                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                                    <label class="block uppercase tracking-wide text-white-700 text-xs font-bold mb-2" for="grid-client-secret">
                                        Client Secret
                                    </label>
                                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-client-secret" name="client_secret" type="text" placeholder="Masukkan Client Secret">
                                </div>
                            </div>
                            <div class="flex flex-wrap -mx-3 mb-6">
                                <div class="w-full px-3">
                                    <label class="block uppercase tracking-wide text-white-700 text-xs font-bold mb-2" for="grid-private-key">
                                        RSA Private Key
                                    </label>
                                    <textarea class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-private-key" name="private_key" type="textbox" placeholder="Masukkan RSA Private Key"></textarea>
                                    <p class="text-white-600 text-xs italic">* Notes : Pastikan Data Yang di Masukkan Benar.</p>
                                </div>
                            </div>
                            <div class="flex items-right py-2">
                                <button class="flex-shrink-0 bg-blue-500 hover:bg-blue-700 border-blue-500 hover:border-blue-700 text-sm border-4 text-white py-1 px-2 rounded" type="submit">
                                Save
                                </button>
                                <button class="flex-shrink-0 border-transparent border-4 text-white-500 hover:text-blue-500 text-sm py-1 px-2 rounded" type="button">
                                Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
