<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail PK: {{ $pk->nomor_pk }}</h2>
            <span class="status-pill {{ $pk->status_konstruksi === 'SELESAI_100' ? 'status-pill-success' : 'status-pill-info' }}">
                Status: {{ $pk->status_konstruksi === 'SELESAI_100' ? 'Selesai 100%' : 'Dalam proses' }}
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php($isComplete = $pk->status_konstruksi === 'SELESAI_100')
            <div class="pk-detail-grid">
                <div class="pk-detail-main">
                    <section class="detail-card">
                        <div class="detail-card-heading">
                            <div><span class="detail-eyebrow">INFORMASI PEKERJAAN</span><h3>{{ $pk->nama_pekerjaan }}</h3></div>
                            <span class="status-pill {{ $isComplete ? 'status-pill-success' : 'status-pill-info' }}">{{ $isComplete ? 'Selesai 100%' : 'Dalam proses' }}</span>
                        </div>
                        <dl class="detail-information">
                            <div><dt>Nomor PK / SPK</dt><dd>{{ $pk->nomor_pk }}</dd></div>
                            <div><dt>Nilai pekerjaan</dt><dd>Rp {{ number_format($pk->nilai_pekerjaan, 0, ',', '.') }}</dd></div>
                            <div><dt>Tahap penagihan</dt><dd>{{ $pk->tahapPenagihan->nomor_tahap ?? 'Belum dikaitkan' }}</dd></div>
                        </dl>
                    </section>

                    @can('upload foto')
                        <section class="detail-card">
                            <div class="detail-card-heading">
                                <div><span class="detail-eyebrow">DOKUMENTASI</span><h3>Upload Foto Dokumentasi Lapangan</h3></div>
                            </div>
                            <form action="{{ route('foto.store', $pk) }}" method="POST" enctype="multipart/form-data" class="photo-upload-form">
                                @csrf
                                <input type="file" name="foto" accept="image/jpeg,image/jpg,image/png" required>
                                <button type="submit" class="app-action-button">Unggah foto</button>
                            </form>
                        </section>
                    @endcan

                    <section class="detail-card">
                        <div class="detail-card-heading">
                            <div><span class="detail-eyebrow">GALERI</span><h3>Dokumentasi pelaksanaan</h3></div>
                            <span class="detail-count">{{ $pk->fotoDokumentasis->count() }} foto</span>
                        </div>
                        @if ($pk->fotoDokumentasis->isEmpty())
                            <p class="detail-empty-message">Belum ada foto dokumentasi yang diunggah untuk PK ini.</p>
                        @else
                            <div class="photo-gallery">
                                @foreach ($pk->fotoDokumentasis as $index => $foto)
                                    <article class="photo-card">
                                        <a href="{{ route('foto.view', $foto) }}" target="_blank" rel="noopener noreferrer" class="photo-preview-link">
                                            <img src="{{ route('foto.view', $foto) }}" alt="Dokumentasi pekerjaan {{ $index + 1 }}" class="photo-thumbnail">
                                        </a>
                                        <div class="photo-caption">
                                            <strong title="{{ $foto->uploader->nama_lengkap ?? 'Uploader' }}">{{ $foto->uploader->nama_lengkap ?? 'System' }}</strong>
                                            <span>{{ $foto->created_at ? $foto->created_at->format('d/m/Y H:i') : '-' }}</span>
                                            @can('delete foto')
                                                @if (auth()->user()->hasAnyRole(['Admin Penagihan', 'Supervisor']) || $foto->uploader_id === auth()->id())
                                                    <form action="{{ route('foto.destroy', $foto) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="photo-delete-button">Hapus foto</button>
                                                    </form>
                                                @endif
                                            @endcan
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </section>
                </div>

                <aside class="detail-card status-tracking-card">
                    <span class="detail-eyebrow">PELACAK STATUS</span>
                    <h3>Progres konstruksi</h3>
                    <ol class="status-timeline">
                        <li class="{{ $isComplete ? 'is-complete' : 'is-current' }}">
                            <span class="status-timeline-marker"></span>
                            <div><strong>Dalam proses</strong><small>{{ $isComplete ? 'Tahap selesai' : 'Pekerjaan sedang berjalan' }}</small></div>
                        </li>
                        <li class="{{ $isComplete ? 'is-current' : 'is-upcoming' }}">
                            <span class="status-timeline-marker"></span>
                            <div><strong>Selesai 100%</strong><small>{{ $isComplete ? 'Pekerjaan telah selesai' : 'Menunggu penyelesaian pekerjaan' }}</small></div>
                        </li>
                    </ol>
                    <div class="tracking-current-status">
                        <span>Status saat ini</span>
                        <strong>{{ $isComplete ? 'Selesai 100%' : 'Dalam proses' }}</strong>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>