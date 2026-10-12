<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail PK: {{ $pk->nomor_pk }}</h2>
            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $pk->status_konstruksi === 'SELESAI_100' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                Status: {{ $pk->status_konstruksi }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Informasi PK -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <p class="text-sm text-gray-600">Nama Pekerjaan: <strong class="text-gray-900">{{ $pk->nama_pekerjaan }}</strong></p>
                <p class="text-sm text-gray-600">Nilai Pekerjaan: <strong class="text-gray-900">Rp {{ number_format($pk->nilai_pekerjaan, 0, ',', '.') }}</strong></p>
                <p class="text-sm text-gray-600">Tahap Penagihan: <strong class="text-gray-900">{{ $pk->tahapPenagihan->nomor_tahap ?? 'Belum Dikaitkan' }}</strong></p>
            </div>

            <!-- Form Upload Foto -->
            @can('upload foto')
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h4 class="font-bold text-md text-gray-700 mb-3">Upload Foto Dokumentasi Lapangan</h4>
                <form action="{{ route('foto.store', $pk) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-4">
                    @csrf
                    <input type="file" name="foto" accept="image/jpeg,image/jpg,image/png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 whitespace-nowrap">
                        + Unggah Foto
                    </button>
                </form>
            </div>
            @endcan

            <!-- Dokumentasi Pelaksanaan Pekerjaan (Model Si Ujang - Kompak & Proporsional) -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-bold text-lg text-gray-800 mb-4">Dokumentasi Pelaksanaan Pekerjaan</h3>

                @if($pk->fotoDokumentasis->isEmpty())
                    <p class="text-gray-500 text-sm italic">Belum ada foto dokumentasi yang diunggah untuk PK ini.</p>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-6 gap-3">
                        @foreach($pk->fotoDokumentasis as $index => $foto)
                            <div class="border rounded-lg overflow-hidden bg-gray-50 shadow-sm flex flex-col justify-between">
                                <div>
                                    <!-- Header Nomor Urut -->
                                    <div class="bg-amber-100 text-amber-800 font-bold text-center py-0.5 text-xs border-b border-amber-200">
                                        {{ $index + 1 }}
                                    </div>
                                    
                                    <!-- Pratinjau Gambar Proporsional -->
                                    <div class="p-1.5">
                                        <a href="{{ asset('storage/' . $foto->file_path_url) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $foto->file_path_url) }}" alt="Foto {{ $index + 1 }}" class="w-full h-28 object-cover rounded border hover:opacity-90 transition">
                                        </a>
                                    </div>
                                </div>

                                <!-- Informasi Uploader & Tanggal Upload -->
                                <div class="p-1.5 bg-white border-t text-[11px] text-gray-600">
                                    <p class="font-medium truncate" title="{{ $foto->uploader->nama_lengkap ?? 'Uploader' }}">
                                        👤 {{ $foto->uploader->nama_lengkap ?? 'System' }}
                                    </p>
                                    <p class="text-gray-400 text-[10px]">
                                        📅 {{ $foto->created_at ? $foto->created_at->format('d/m/Y H:i') : '-' }}
                                    </p>

                                    <!-- Tombol Hapus Foto -->
                                    @can('delete foto')
                                    <form action="{{ route('foto.destroy', $foto) }}" method="POST" class="mt-1" onsubmit="return confirm('Hapus foto ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full py-0.5 bg-red-50 text-red-600 font-semibold rounded hover:bg-red-100 text-center transition text-[11px]">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>