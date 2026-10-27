@php
    $tipe = $tipe ?? 'artikel';
    $mediaId = $item['id'] ?? ($tipe . '-' . md5($item['judul'] ?? uniqid()));
    $durasiClean = isset($item['durasi']) ? trim(str_replace(['Durasi', 'min', 'Min'], '', $item['durasi'])) : '35';
    if (!str_contains($durasiClean, ':')) {
        $durasiFormatted = sprintf('%02d:00', (int)$durasiClean);
    } else {
        $durasiFormatted = $durasiClean;
    }
    $durasiSeconds = $item['durasi_detik'] ?? 2100;
@endphp

<div class="rehat-card"
     id="rehatCard-{{ $mediaId }}"
     data-tipe="{{ $tipe }}"
     data-media-id="{{ $mediaId }}"
     data-media-url="{{ $item['media_url'] ?? '' }}">

    <div class="rehat-card__thumb-wrapper {{ $tipe === 'video' ? 'rehat-card__thumb-wrapper--video' : '' }}">
        @if($tipe === 'audio')

            <div class="rehat-card__audio-thumb" style="background-color: {{ $item['warna'] ?? '#4F4F4F' }};">
                <div class="rehat-card__audio-wave" id="audioWave-{{ $mediaId }}" aria-hidden="true">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                @include('admin.components.play-pause-button', [
                    'mediaId' => $mediaId,
                    'mediaType' => 'audio',
                    'title' => $item['judul'] ?? 'Audio',
                ])
            </div>

        @elseif($tipe === 'video')

            @include('admin.components.video-player', [
                'mediaId' => $mediaId,
                'mediaUrl' => $item['media_url'] ?? '',
                'thumbnail' => !empty($item['gambar']) && file_exists(public_path('images/admin/rehat/' . $item['gambar']))
                    ? asset('images/admin/rehat/' . $item['gambar']) . '?v=' . filemtime(public_path('images/admin/rehat/' . $item['gambar']))
                    : '',
                'title' => $item['judul'] ?? 'Video',
                'duration' => $durasiFormatted,
                'durationSeconds' => $durasiSeconds,
            ])

        @else

            @if(!empty($item['gambar']) && file_exists(public_path('images/admin/rehat/' . $item['gambar'])))
                <img src="{{ asset('images/admin/rehat/' . $item['gambar']) }}?v={{ filemtime(public_path('images/admin/rehat/' . $item['gambar'])) }}"
                     alt="{{ $item['judul'] }}"
                     class="rehat-card__thumb"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="rehat-card__thumb-placeholder" style="display: none;"></div>
            @else
                <div class="rehat-card__thumb-placeholder"></div>
            @endif
        @endif
    </div>

    <div class="rehat-card__content">
        <h3 class="rehat-card__title" title="{{ $item['judul'] }}">{{ $item['judul'] }}</h3>
        <p class="rehat-card__desc">{{ $item['deskripsi'] }}</p>

        @if($tipe === 'audio')
            <div class="rehat-media-error" id="audioError-{{ $mediaId }}" style="display: none;">
                <span class="material-icons-round">error_outline</span>
                <span>Media tidak dapat diputar</span>
            </div>
        @endif

        @if($tipe === 'audio')
            @include('admin.components.audio-player-bar', [
                'mediaId' => $mediaId,
                'mediaUrl' => $item['media_url'] ?? '',
                'duration' => $durasiFormatted,
                'durationSeconds' => $durasiSeconds,
            ])
        @endif

        <div class="rehat-card__meta">
            @if(isset($item['penulis']))
                <span class="rehat-card__author">{{ $item['penulis'] }}</span>
            @endif
            @if(isset($item['baca']))
                <span class="rehat-card__views">{{ $item['baca'] }}</span>
            @endif
            @if(isset($item['diputar']))
                <span class="rehat-card__views">{{ $item['diputar'] }}</span>
            @endif
            @if(isset($item['durasi']))
                <span class="rehat-card__duration">{{ $item['durasi'] }}</span>
            @endif

            @if(in_array($tipe, ['audio', 'video']))
                <span class="rehat-card__playing-status" id="playingStatus-{{ $mediaId }}" style="display: none;">
                    <span class="rehat-pulse-dot"></span>
                    <span class="playing-status-text">Sedang diputar</span>
                </span>
            @endif
        </div>
    </div>

    <div class="rehat-card__badge rehat-card__badge--{{ strtolower($item['status']) === 'dipublikasi' ? 'published' : 'draft' }}">
        {{ $item['status'] }}
    </div>

    <div class="rehat-card__actions">
        @if(strtolower($item['status']) === 'draf')
            <button type="button" class="rehat-btn rehat-btn--terbitkan" onclick="alert('Konten berhasil diterbitkan!')">
                TERBITKAN
            </button>
        @endif
        <button type="button" class="rehat-btn rehat-btn--edit" onclick="alert('Edit konten: {{ addslashes($item['judul']) }}')">
            EDIT
        </button>
        <button type="button" class="rehat-btn rehat-btn--hapus" onclick="if(confirm('Apakah Anda yakin ingin menghapus konten ini?')) { alert('Konten dihapus'); }">
            HAPUS
        </button>
    </div>
</div>
