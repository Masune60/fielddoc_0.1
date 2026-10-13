<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Kontrak Rinci (KR)</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('kr.update', $kr) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="khs_id" value="Pilih Payung Hukum (KHS)" />
                        <select id="khs_id" name="khs_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                            @foreach ($khsList as $khs)
                                <option value="{{ $khs->id }}" {{ old('khs_id', $kr->khs_id) == $khs->id ? 'selected' : '' }}>{{ $khs->nomor_khs }} - {{ $khs->judul_kontrak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="nomor_kr" value="Nomor KR" />
                        <x-text-input id="nomor_kr" class="block mt-1 w-full" type="text" name="nomor_kr" :value="old('nomor_kr', $kr->nomor_kr)" required />
                    </div>
                    <div>
                        <x-input-label for="spesifikasi_teknis" value="Spesifikasi Teknis" />
                        <textarea id="spesifikasi_teknis" name="spesifikasi_teknis" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" rows="3">{{ old('spesifikasi_teknis', $kr->spesifikasi_teknis) }}</textarea>
                    </div>
                    <div>
                        <x-input-label for="syarat_apd_k3" value="Syarat APD K3" />
                        <textarea id="syarat_apd_k3" name="syarat_apd_k3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" rows="2">{{ old('syarat_apd_k3', $kr->syarat_apd_k3) }}</textarea>
                    </div>
                    <div>
                        <x-input-label for="standar_material" value="Standar Material" />
                        <textarea id="standar_material" name="standar_material" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" rows="2">{{ old('standar_material', $kr->standar_material) }}</textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('khs.show', $kr->khs_id) }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md text-sm">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">Update KR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
