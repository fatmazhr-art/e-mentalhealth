{{-- 
    Komponen Reusable: VideoPlayer
    Pemutar video HTML5 dengan custom controls:
    - Play / Pause
    - Progress bar yang bisa digeser (maroon #80001E / abu #D9D4D0)
    - Waktu berjalan / total (contoh 02:15 / 35:00)
    - Volume / Mute
    - Fullscreen
--}}
@props([
    'mediaId' => 'video-' . uniqid(),
    'mediaUrl' => '',
    'thumbnail' => '',
    'title' => 'Video',
    'duration' => '35:00',
    'durationSeconds' => 2100,
])

<div class="rehat-video-wrapper" id="videoWrapper-{{ $mediaId }}" data-media-id="{{ $mediaId }}">
    <!-- 1. Tampilan Awal: Thumbnail + Tombol Play Semi-Transparan -->
    <div class="rehat-video-poster" id="videoPoster-{{ $mediaId }}">
        @if(!empty($thumbnail))
            <img src="{{ $thumbnail }}" alt="{{ $title }}" class="rehat-card__thumb">
        @else
            <div class="rehat-card__thumb-placeholder"></div>
        @endif
        <div class="rehat-video-poster__overlay"></div>

        <button type="button" 
                class="rehat-play-pause-btn media-play-btn rehat-video-poster__play-btn" 
                id="btnPosterPlay-{{ $mediaId }}"
                data-media-id="{{ $mediaId }}"
                data-media-type="video"
                aria-label="Putar video {{ $title }}"
                title="Putar">
            <span class="media-icon-play" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </span>
            <span class="media-icon-pause" aria-hidden="true" style="display: none;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                </svg>
            </span>
        </button>
    </div>

    <!-- 2. Tampilan Pemutar Video Aktif (Menggantikan Thumbnail Saat Diputar) -->
    <div class="rehat-video-player" id="videoPlayer-{{ $mediaId }}" style="display: none;">
        <!-- HTML5 Video Element bawaan -->
        <video class="rehat-video-element" 
               id="videoElement-{{ $mediaId }}" 
               src="{{ $mediaUrl }}" 
               preload="metadata"
               playsinline
               data-media-id="{{ $mediaId }}">
            Browser Anda tidak mendukung tag video HTML5.
        </video>

        <!-- Pesan Error Fallback jika file gagal dimuat -->
        <div class="rehat-media-error" id="videoError-{{ $mediaId }}" style="display: none;">
            <span class="material-icons-round">error_outline</span>
            <span>Media tidak dapat diputar</span>
        </div>

        <!-- Custom Video Controls Bar -->
        <div class="rehat-video-controls" id="videoControls-{{ $mediaId }}">
            <!-- Play/Pause Button -->
            <button type="button" 
                    class="video-ctrl-btn video-ctrl-play" 
                    id="videoPlayBtn-{{ $mediaId }}"
                    data-media-id="{{ $mediaId }}"
                    aria-label="Play / Pause">
                <span class="material-icons-round ctrl-icon-play">play_arrow</span>
                <span class="material-icons-round ctrl-icon-pause" style="display: none;">pause</span>
            </button>

            <!-- Draggable Progress Slider -->
            <div class="video-ctrl-slider-wrap">
                <input type="range" 
                       class="video-ctrl-slider" 
                       id="videoProgress-{{ $mediaId }}" 
                       min="0" 
                       max="{{ $durationSeconds }}" 
                       value="0" 
                       step="0.5" 
                       aria-label="Seek video">
            </div>

            <!-- Time Display: 00:00 / 35:00 -->
            <div class="video-ctrl-time">
                <span class="video-time-cur" id="videoCurTime-{{ $mediaId }}">00:00</span>
                <span class="video-time-sep">/</span>
                <span class="video-time-dur" id="videoDurTime-{{ $mediaId }}">{{ $duration }}</span>
            </div>

            <!-- Volume / Mute -->
            <button type="button" 
                    class="video-ctrl-btn video-ctrl-vol" 
                    id="videoVolBtn-{{ $mediaId }}"
                    data-media-id="{{ $mediaId }}"
                    aria-label="Mute / Unmute"
                    title="Mute / Unmute">
                <span class="material-icons-round">volume_up</span>
            </button>

            <!-- Fullscreen Button -->
            <button type="button" 
                    class="video-ctrl-btn video-ctrl-fullscreen" 
                    id="videoFullscreenBtn-{{ $mediaId }}"
                    data-media-id="{{ $mediaId }}"
                    aria-label="Layar penuh"
                    title="Layar penuh">
                <span class="material-icons-round">fullscreen</span>
            </button>
        </div>
    </div>
</div>
