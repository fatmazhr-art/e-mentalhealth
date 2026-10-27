@props([
    'mediaId' => 'audio-' . uniqid(),
    'mediaUrl' => '',
    'duration' => '35:00',
    'durationSeconds' => 2100,
])

<div class="rehat-audio-player-bar" id="audioPlayerBar-{{ $mediaId }}" data-media-id="{{ $mediaId }}" style="display: none;">

    <audio class="rehat-audio-element"
           id="audioElement-{{ $mediaId }}"
           src="{{ $mediaUrl }}"
           preload="metadata"
           data-media-id="{{ $mediaId }}">
    </audio>

    <span class="audio-player__time-current" id="audioCurrentTime-{{ $mediaId }}">00:00</span>

    <div class="audio-player__slider-container">
        <input type="range"
               class="audio-player__progress-slider"
               id="audioProgress-{{ $mediaId }}"
               min="0"
               max="{{ $durationSeconds }}"
               value="0"
               step="0.5"
               aria-label="Posisi pemutaran audio">
    </div>

    <span class="audio-player__time-duration" id="audioTotalDuration-{{ $mediaId }}">{{ $duration }}</span>

    <button type="button"
            class="audio-player__btn-volume"
            id="audioVolumeBtn-{{ $mediaId }}"
            data-media-id="{{ $mediaId }}"
            aria-label="Pengaturan suara"
            title="Mute / Unmute">
        <span class="material-icons-round">volume_up</span>
    </button>
</div>
