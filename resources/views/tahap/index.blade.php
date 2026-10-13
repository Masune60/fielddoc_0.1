<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Tahap Penagihan</h2>
            @can('manage tahap')
            <a href="{{ route('tahap.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">+ Buat Tahap Penagihan</a>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahap</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontrak Rinci (KR)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pengajuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah PK</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($tahapList as $tahap)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                                    <a href="{{ route('tahap.show', $tahap) }}">{{ $tahap->nomor_tahap }}</a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $tahap->kr->nomor_kr ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $tahap->tanggal_pengajuan ?? 'Belum Diajukan' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $tahap->pks->count() }} PK</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $tahap->status_penagihan === 'DICAIRKAN' ? 'bg-green-100 text-green-800' : ($tahap->status_penagihan === 'DIAJUKAN' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $tahap->status_penagihan }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <a href="{{ route('tahap.show', $tahap) }}" class="text-blue-600 hover:text-blue-900 mr-2">Detail PK</a>
                                    @can('manage tahap')
                                    <a href="{{ route('tahap.edit', $tahap) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                    <form action="{{ route('tahap.destroy', $tahap) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus Tahap ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $tahapList->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>