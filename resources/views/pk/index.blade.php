<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master PK / SPK (Surat Perintah Kerja)</h2>
            @can('create pk')
            <a href="{{ route('pk.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">+ Tambah PK Baru</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor PK</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahap Penagihan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Pekerjaan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai Pekerjaan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Konstruksi</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($pks as $pk)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                                    @can('view pk details')
                                    <a href="{{ route('pk.show', $pk) }}">{{ $pk->nomor_pk }}</a>
                                    @else
                                    {{ $pk->nomor_pk }}
                                    @endcan
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $pk->tahapPenagihan->nomor_tahap ?? 'Unassigned' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $pk->nama_pekerjaan }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($pk->nilai_pekerjaan, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $pk->status_konstruksi === 'SELESAI_100' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $pk->status_konstruksi }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    @can('view pk details')
                                    <a href="{{ route('pk.show', $pk) }}" class="text-blue-600 hover:text-blue-900 mr-2">Foto</a>
                                    @endcan
                                    @can('edit pk')
                                    <a href="{{ route('pk.edit', $pk) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                    <form action="{{ route('pk.destroy', $pk) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus PK ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                    @endcan
                                    @can('upload foto')
                                    <form action="{{ route('foto.store', $pk) }}" method="POST" enctype="multipart/form-data" class="mt-2 flex flex-col items-end gap-1">
                                        @csrf
                                        <input type="file" name="foto" accept="image/jpeg,image/jpg,image/png" required class="block w-full text-xs text-gray-500">
                                        <button type="submit" class="px-3 py-1 bg-indigo-600 text-white rounded text-xs font-medium hover:bg-indigo-700">Unggah Foto</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $pks->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>