<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Tahap: {{ $tahap->nomor_tahap }}
        </h2>
        <div class="flex gap-2">
            @can('export zip')
            <a href="{{ route('tahap.export', $tahap) }}" class="px-4 py-2 bg-purple-600 text-white rounded-md text-sm font-medium hover:bg-purple-700">
                📦 Export Paket ZIP Berstruktur
            </a>
            @endcan
            @can('create pk')
            <a href="{{ route('pk.create', ['tahap_id' => $tahap->id]) }}" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700">
                + Tambah PK / SPK Pekerjaan
            </a>
            @endcan
        </div>
    </div>
</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <p class="text-sm text-gray-500">Payung Hukum: <strong class="text-gray-800">{{ $tahap->kr->khs->nomor_khs ?? '-' }}</strong></p>
                <p class="text-sm text-gray-500">Kontrak Rinci: <strong class="text-gray-800">{{ $tahap->kr->nomor_kr ?? '-' }}</strong></p>
                <p class="text-sm text-gray-500">Status Penagihan: <span class="font-bold text-indigo-600">{{ $tahap->status_penagihan }}</span></p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h4 class="font-bold text-md mb-4 text-gray-700">Daftar PK (Pekerjaan Fisik Lapangan)</h4>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor PK/SPK</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi Pekerjaan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai (Rp)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Konstruksi</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($tahap->pks as $pk)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $pk->nomor_pk }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $pk->lokasi_pekerjaan }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-semibold">Rp {{ number_format($pk->nilai_pekerjaan, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $pk->status_konstruksi === 'SELESAI_100' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $pk->status_konstruksi }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <a href="{{ route('pk.show', $pk) }}" class="text-blue-600 hover:text-blue-900 mr-2">Lihat Foto</a>
                                    @can('edit pk')
                                    <a href="{{ route('pk.edit', $pk) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada PK/SPK yang dikaitkan dengan Tahap ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>