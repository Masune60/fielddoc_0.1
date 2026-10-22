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

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @php
                $statusSteps = ['DRAFT' => 'Draft', 'DIAJUKAN' => 'Diajukan', 'DICAIRKAN' => 'Dicairkan'];
                $currentStatusIndex = array_search($tahap->status_penagihan, array_keys($statusSteps), true);
            @endphp
            <div class="tahap-detail-grid">
                <section class="detail-card">
                    <div class="detail-card-heading">
                        <div><span class="detail-eyebrow">RINGKASAN PENAGIHAN</span><h3>{{ $tahap->nomor_tahap }}</h3></div>
                        <span class="status-pill {{ $tahap->status_penagihan === 'DICAIRKAN' ? 'status-pill-success' : ($tahap->status_penagihan === 'DIAJUKAN' ? 'status-pill-info' : 'status-pill-pending') }}">{{ $statusSteps[$tahap->status_penagihan] }}</span>
                    </div>
                    <dl class="detail-information">
                        <div><dt>Payung hukum</dt><dd>{{ $tahap->kr->khs->nomor_khs ?? '-' }}</dd></div>
                        <div><dt>Kontrak rinci</dt><dd>{{ $tahap->kr->nomor_kr ?? '-' }}</dd></div>
                        <div><dt>Tanggal pengajuan</dt><dd>{{ $tahap->tanggal_pengajuan ?? 'Belum diajukan' }}</dd></div>
                    </dl>
                </section>

                <aside class="detail-card status-tracking-card">
                    <span class="detail-eyebrow">PELACAK STATUS</span>
                    <h3>Progres penagihan</h3>
                    <ol class="status-timeline">
                        @foreach ($statusSteps as $status => $label)
                            <li class="{{ $loop->index < $currentStatusIndex ? 'is-complete' : ($loop->index === $currentStatusIndex ? 'is-current' : 'is-upcoming') }}">
                                <span class="status-timeline-marker"></span>
                                <div><strong>{{ $label }}</strong><small>{{ $loop->index === $currentStatusIndex ? 'Status saat ini' : ($loop->index < $currentStatusIndex ? 'Selesai' : 'Menunggu tahap sebelumnya') }}</small></div>
                            </li>
                        @endforeach
                    </ol>
                </aside>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm detail-card">
                <div class="detail-card-heading">
                    <div><span class="detail-eyebrow">PEKERJAAN FISIK LAPANGAN</span><h3>Daftar PK / SPK</h3></div>
                    <span class="detail-count">{{ $tahap->pks->count() }} pekerjaan</span>
                </div>
                <div class="data-table-scroll">
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
                                    <span class="status-pill {{ $pk->status_konstruksi === 'SELESAI_100' ? 'status-pill-success' : 'status-pill-info' }}">
                                        {{ $pk->status_konstruksi === 'SELESAI_100' ? 'Selesai 100%' : 'Dalam proses' }}
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
    </div>
</x-app-layout>