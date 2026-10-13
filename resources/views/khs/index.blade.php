<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Data KHS (Kontrak Harga Satuan)</h2>
            @can('manage khs')
            <a href="{{ route('khs.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">+ Tambah KHS</a>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor KHS</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Kontrak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Masa Berlaku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah KR</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($khsList as $khs)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                                    <a href="{{ route('khs.show', $khs) }}">{{ $khs->nomor_khs }}</a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $khs->judul_kontrak }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $khs->tanggal_mulai }} s/d {{ $khs->tanggal_selesai }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $khs->krs_count }} KR</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $khs->status_kontrak === 'AKTIF' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $khs->status_kontrak }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <a href="{{ route('khs.show', $khs) }}" class="text-blue-600 hover:text-blue-900 mr-2">Detail KR</a>
                                    @can('manage khs')
                                    <a href="{{ route('khs.edit', $khs) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                    <form action="{{ route('khs.destroy', $khs) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus KHS ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $khsList->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>