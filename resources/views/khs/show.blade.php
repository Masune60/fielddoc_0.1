<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail KHS: {{ $kh->nomor_khs }}</h2>
            @can('manage kr')
            <a href="{{ route('kr.create', ['khs_id' => $kh->id]) }}" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700">+ Tambah KR (Kontrak Rinci)</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold mb-2">{{ $kh->judul_kontrak }}</h3>
                <p class="text-sm text-gray-600">Periode: {{ $kh->tanggal_mulai }} s/d {{ $kh->tanggal_selesai }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h4 class="font-bold text-md mb-4 text-gray-700">Daftar Kontrak Rinci (KR) di Bawah KHS Ini</h4>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor KR</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Spesifikasi Teknis</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($kh->krs as $kr)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $kr->nomor_kr }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $kr->spesifikasi_teknis ?? '-' }}</td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    @can('manage kr')
                                    <a href="{{ route('kr.edit', $kr) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                    <form action="{{ route('kr.destroy', $kr) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus KR ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                    @else
                                    <span class="text-gray-400 text-xs">Read-only</span>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada Kontrak Rinci (KR) yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>