document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchRehat');
    const btnUnggah = document.getElementById('btnUnggahKonten');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.rehat-card');

            cards.forEach(card => {
                const title = card.querySelector('.rehat-card__title')?.textContent.toLowerCase() || '';
                if (title.includes(query)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    const modal = document.getElementById('modalUnggahMedia');
    const btnTutup = document.getElementById('btnTutupModal');

    function openModal() {
        if (modal) {
            modal.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal() {
        if (modal) {
            modal.classList.remove('is-open');
            document.body.style.overflow = '';
        }
    }

    if (btnUnggah) {
        btnUnggah.addEventListener('click', openModal);
    }

    if (btnTutup) {
        btnTutup.addEventListener('click', closeModal);
    }

    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                closeModal();
            }
        });
    }

    const btnFormatAudio = document.getElementById('btnFormatAudio');
    const btnFormatVideo = document.getElementById('btnFormatVideo');
    const btnFormatArtikel = document.getElementById('btnFormatArtikel');
    const formSectionAudio = document.getElementById('formSectionAudio');
    const formSectionVideo = document.getElementById('formSectionVideo');
    const formSectionArtikel = document.getElementById('formSectionArtikel');

    function setActiveFormat(format) {
        if (btnFormatAudio) btnFormatAudio.classList.remove('is-active');
        if (btnFormatVideo) btnFormatVideo.classList.remove('is-active');
        if (btnFormatArtikel) btnFormatArtikel.classList.remove('is-active');

        if (formSectionAudio) formSectionAudio.style.display = 'none';
        if (formSectionVideo) formSectionVideo.style.display = 'none';
        if (formSectionArtikel) formSectionArtikel.style.display = 'none';

        if (format === 'video') {
            if (btnFormatVideo) btnFormatVideo.classList.add('is-active');
            if (formSectionVideo) formSectionVideo.style.display = 'block';
        } else if (format === 'audio') {
            if (btnFormatAudio) btnFormatAudio.classList.add('is-active');
            if (formSectionAudio) formSectionAudio.style.display = 'block';
        } else if (format === 'artikel') {
            if (btnFormatArtikel) btnFormatArtikel.classList.add('is-active');
            if (formSectionArtikel) formSectionArtikel.style.display = 'block';
        }
    }

    if (btnFormatVideo) {
        btnFormatVideo.addEventListener('click', function () {
            setActiveFormat('video');
        });
    }

    if (btnFormatAudio) {
        btnFormatAudio.addEventListener('click', function () {
            setActiveFormat('audio');
        });
    }

    if (btnFormatArtikel) {
        btnFormatArtikel.addEventListener('click', function () {
            setActiveFormat('artikel');
        });
    }

    function initTagPill(tagEl) {
        if (!tagEl || tagEl.classList.contains('rehat-tag-pill--add')) return;

        tagEl.addEventListener('click', function (e) {
            if (e.target.closest('.rehat-tag-pill__remove')) return;
            tagEl.classList.toggle('is-inactive');
        });

        const textSpan = tagEl.querySelector('span, .rehat-tag-pill__text');
        if (textSpan) {
            textSpan.setAttribute('title', 'Klik untuk pilih/batal, klik dua kali untuk ubah teks');
            textSpan.addEventListener('dblclick', function (e) {
                e.stopPropagation();
                const currentText = textSpan.textContent.trim();
                const newText = prompt('Ubah nama topik atau kategori masalah:', currentText);
                if (newText && newText.trim() !== '') {
                    textSpan.textContent = newText.trim();
                }
            });
        }

        const removeBtn = tagEl.querySelector('.rehat-tag-pill__remove');
        if (removeBtn) {
            removeBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                tagEl.remove();
            });
        }
    }

    document.querySelectorAll('.rehat-tags-row .rehat-tag-pill:not(.rehat-tag-pill--add)').forEach(initTagPill);

    window.tambahTagInline = function (btn) {
        if (!btn) return;
        const row = btn.parentElement;
        if (!row) return;

        let existingInput = row.querySelector('.rehat-tag-input-inline');
        if (existingInput) {
            existingInput.focus();
            return;
        }

        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'rehat-tag-input-inline';
        input.placeholder = 'Ketik topik...';
        row.insertBefore(input, btn);
        input.focus();

        let isCommitted = false;
        function commitTag() {
            if (isCommitted) return;
            isCommitted = true;
            const val = input.value.trim();
            input.remove();

            if (val !== '') {
                const tagEl = document.createElement('div');
                tagEl.className = 'rehat-tag-pill';
                tagEl.title = 'Klik untuk memilih/membatalkan, klik dua kali untuk ubah teks';
                tagEl.setAttribute('role', 'button');
                tagEl.setAttribute('tabindex', '0');
                tagEl.onclick = function() { this.classList.toggle('is-inactive'); };
                tagEl.innerHTML = `<span class="rehat-tag-pill__text" ondblclick="event.stopPropagation(); let t = prompt('Ubah nama topik/kategori:', this.textContent.trim()); if(t) this.textContent = t.trim();">${val}</span><button type="button" class="rehat-tag-pill__remove" onclick="event.stopPropagation(); this.parentElement.remove()" aria-label="Hapus tag">&times;</button>`;
                row.insertBefore(tagEl, btn);
                initTagPill(tagEl);
            }
        }

        input.addEventListener('keydown', function (evt) {
            if (evt.key === 'Enter') {
                evt.preventDefault();
                commitTag();
            } else if (evt.key === 'Escape') {
                isCommitted = true;
                input.remove();
            }
        });

        input.addEventListener('blur', function () {
            commitTag();
        });
    };

    function setupTagAddition(btnId, rowId) {
        const btn = document.getElementById(btnId);
        if (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                window.tambahTagInline(btn);
            });
        }
    }

    setupTagAddition('btnTambahTag', 'rehatTagsRow');
    setupTagAddition('btnTambahTagVideo', 'rehatTagsRowVideo');

    const RehatMediaManager = {
        currentMedia: null,

        formatTime: function (seconds) {
            if (isNaN(seconds) || seconds < 0) return '00:00';
            const m = Math.floor(seconds / 60);
            const s = Math.floor(seconds % 60);
            return `${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
        },

        updateSliderFill: function (slider, currentVal, maxVal) {
            if (!slider) return;
            const max = maxVal || parseFloat(slider.max) || 100;
            const pct = max > 0 ? (currentVal / max) * 100 : 0;
            const boundedPct = Math.min(100, Math.max(0, pct));
            slider.style.background = `linear-gradient(to right, #80001E 0%, #80001E ${boundedPct}%, #D9D4D0 ${boundedPct}%, #D9D4D0 100%)`;
        },

        pauseCurrentMedia: function () {
            if (this.currentMedia && this.currentMedia.element) {
                try {
                    this.currentMedia.element.pause();
                } catch (e) {
                    console.warn('Error pausing media:', e);
                }
                const oldId = this.currentMedia.id;
                const oldType = this.currentMedia.type;

                this.setMediaUiPaused(oldId, oldType);
                this.currentMedia = null;
            }
        },

        setMediaUiPaused: function (id, type) {
            const card = document.getElementById(`rehatCard-${id}`);
            if (card) {
                card.classList.remove('is-playing');
                card.classList.add('is-paused');
            }

            const playButtons = document.querySelectorAll(`[data-media-id="${id}"]`);
            playButtons.forEach(btn => {
                const playIcon = btn.querySelector('.media-icon-play, .ctrl-icon-play');
                const pauseIcon = btn.querySelector('.media-icon-pause, .ctrl-icon-pause');
                if (playIcon) playIcon.style.display = '';
                if (pauseIcon) pauseIcon.style.display = 'none';
                btn.setAttribute('aria-label', 'Putar');
                btn.setAttribute('title', 'Putar');
            });
        },

        setMediaUiPlaying: function (id, type) {
            const card = document.getElementById(`rehatCard-${id}`);
            if (card) {
                card.classList.add('is-active-media');
                card.classList.add('is-playing');
                card.classList.remove('is-paused');
            }

            const playButtons = document.querySelectorAll(`[data-media-id="${id}"]`);
            playButtons.forEach(btn => {
                const playIcon = btn.querySelector('.media-icon-play, .ctrl-icon-play');
                const pauseIcon = btn.querySelector('.media-icon-pause, .ctrl-icon-pause');
                if (playIcon) playIcon.style.display = 'none';
                if (pauseIcon) pauseIcon.style.display = '';
                btn.setAttribute('aria-label', 'Jeda');
                btn.setAttribute('title', 'Jeda');
            });

            const statusBadge = document.getElementById(`playingStatus-${id}`);
            if (statusBadge) {
                statusBadge.style.display = 'inline-flex';
            }
        },

        setMediaUiStopped: function (id, type) {
            const card = document.getElementById(`rehatCard-${id}`);
            if (card) {
                card.classList.remove('is-active-media', 'is-playing', 'is-paused');
            }

            const playButtons = document.querySelectorAll(`[data-media-id="${id}"]`);
            playButtons.forEach(btn => {
                const playIcon = btn.querySelector('.media-icon-play, .ctrl-icon-play');
                const pauseIcon = btn.querySelector('.media-icon-pause, .ctrl-icon-pause');
                if (playIcon) playIcon.style.display = '';
                if (pauseIcon) pauseIcon.style.display = 'none';
                btn.setAttribute('aria-label', 'Putar');
                btn.setAttribute('title', 'Putar');
            });

            const statusBadge = document.getElementById(`playingStatus-${id}`);
            if (statusBadge) {
                statusBadge.style.display = 'none';
            }

            if (type === 'audio') {
                const audioBar = document.getElementById(`audioPlayerBar-${id}`);
                if (audioBar) audioBar.style.display = 'none';
            }
        },

        handleMediaError: function (id, type) {
            console.error(`Media gagal dimuat untuk ID: ${id} (${type})`);

            const errEl = document.getElementById(type === 'audio' ? `audioError-${id}` : `videoError-${id}`);
            if (errEl) {
                errEl.style.display = 'flex';
            }

            const playButtons = document.querySelectorAll(`[data-media-id="${id}"]`);
            playButtons.forEach(btn => {
                btn.disabled = true;
                btn.classList.add('is-disabled');
                btn.setAttribute('title', 'Media tidak dapat diputar');
            });

            this.setMediaUiStopped(id, type);
        }
    };

    const audioCards = document.querySelectorAll('.rehat-card[data-tipe="audio"]');

    audioCards.forEach(card => {
        const mediaId = card.getAttribute('data-media-id');
        const audioElement = document.getElementById(`audioElement-${mediaId}`);
        const playBtn = card.querySelector(`.rehat-play-pause-btn[data-media-id="${mediaId}"]`);
        const audioBar = document.getElementById(`audioPlayerBar-${mediaId}`);
        const progressSlider = document.getElementById(`audioProgress-${mediaId}`);
        const curTimeText = document.getElementById(`audioCurrentTime-${mediaId}`);
        const totalDurationText = document.getElementById(`audioTotalDuration-${mediaId}`);
        const volumeBtn = document.getElementById(`audioVolumeBtn-${mediaId}`);

        if (!audioElement) return;

        RehatMediaManager.updateSliderFill(progressSlider, 0, progressSlider?.max || 100);

        audioElement.addEventListener('loadedmetadata', function () {
            if (audioElement.duration && !isNaN(audioElement.duration) && isFinite(audioElement.duration)) {
                if (progressSlider) {
                    progressSlider.max = audioElement.duration;
                }
                if (totalDurationText) {
                    totalDurationText.textContent = RehatMediaManager.formatTime(audioElement.duration);
                }
            }
        });

        audioElement.addEventListener('timeupdate', function () {
            if (curTimeText) {
                curTimeText.textContent = RehatMediaManager.formatTime(audioElement.currentTime);
            }
            if (progressSlider && !progressSlider.matches(':active')) {
                progressSlider.value = audioElement.currentTime;
                RehatMediaManager.updateSliderFill(progressSlider, audioElement.currentTime, progressSlider.max);
            }
        });

        if (progressSlider) {
            progressSlider.addEventListener('input', function () {
                const targetTime = parseFloat(this.value);
                if (curTimeText) {
                    curTimeText.textContent = RehatMediaManager.formatTime(targetTime);
                }
                RehatMediaManager.updateSliderFill(this, targetTime, this.max);
            });

            progressSlider.addEventListener('change', function () {
                audioElement.currentTime = parseFloat(this.value);
            });
        }

        audioElement.addEventListener('ended', function () {
            RehatMediaManager.setMediaUiStopped(mediaId, 'audio');
            if (progressSlider) {
                progressSlider.value = 0;
                RehatMediaManager.updateSliderFill(progressSlider, 0, progressSlider.max);
            }
            if (curTimeText) {
                curTimeText.textContent = '00:00';
            }
            RehatMediaManager.currentMedia = null;
        });

        audioElement.addEventListener('error', function () {
            RehatMediaManager.handleMediaError(mediaId, 'audio');
        });

        if (volumeBtn) {
            volumeBtn.addEventListener('click', function () {
                audioElement.muted = !audioElement.muted;
                const icon = volumeBtn.querySelector('.material-icons-round');
                if (icon) {
                    icon.textContent = audioElement.muted ? 'volume_off' : 'volume_up';
                }
            });
        }

        if (playBtn) {
            playBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                if (playBtn.disabled) return;

                if (!audioElement.paused) {
                    audioElement.pause();
                    RehatMediaManager.setMediaUiPaused(mediaId, 'audio');
                    RehatMediaManager.currentMedia = null;
                } else {

                    RehatMediaManager.pauseCurrentMedia();

                    if (audioBar) {
                        audioBar.style.display = 'flex';
                    }

                    audioElement.play().then(() => {
                        RehatMediaManager.currentMedia = {
                            type: 'audio',
                            id: mediaId,
                            element: audioElement
                        };
                        RehatMediaManager.setMediaUiPlaying(mediaId, 'audio');
                    }).catch(err => {
                        RehatMediaManager.handleMediaError(mediaId, 'audio');
                    });
                }
            });
        }
    });

    const videoCards = document.querySelectorAll('.rehat-card[data-tipe="video"]');

    videoCards.forEach(card => {
        const mediaId = card.getAttribute('data-media-id');
        const videoElement = document.getElementById(`videoElement-${mediaId}`);
        const posterWrap = document.getElementById(`videoPoster-${mediaId}`);
        const videoPlayerWrap = document.getElementById(`videoPlayer-${mediaId}`);
        const posterPlayBtn = document.getElementById(`btnPosterPlay-${mediaId}`);
        const ctrlPlayBtn = document.getElementById(`videoPlayBtn-${mediaId}`);
        const progressSlider = document.getElementById(`videoProgress-${mediaId}`);
        const curTimeText = document.getElementById(`videoCurTime-${mediaId}`);
        const durTimeText = document.getElementById(`videoDurTime-${mediaId}`);
        const volBtn = document.getElementById(`videoVolBtn-${mediaId}`);
        const fullscreenBtn = document.getElementById(`videoFullscreenBtn-${mediaId}`);

        if (!videoElement) return;

        RehatMediaManager.updateSliderFill(progressSlider, 0, progressSlider?.max || 100);

        videoElement.addEventListener('loadedmetadata', function () {
            if (videoElement.duration && !isNaN(videoElement.duration) && isFinite(videoElement.duration)) {
                if (progressSlider) {
                    progressSlider.max = videoElement.duration;
                }
                if (durTimeText) {
                    durTimeText.textContent = RehatMediaManager.formatTime(videoElement.duration);
                }
            }
        });

        videoElement.addEventListener('timeupdate', function () {
            if (curTimeText) {
                curTimeText.textContent = RehatMediaManager.formatTime(videoElement.currentTime);
            }
            if (progressSlider && !progressSlider.matches(':active')) {
                progressSlider.value = videoElement.currentTime;
                RehatMediaManager.updateSliderFill(progressSlider, videoElement.currentTime, progressSlider.max);
            }
        });

        if (progressSlider) {
            progressSlider.addEventListener('input', function () {
                const targetTime = parseFloat(this.value);
                if (curTimeText) {
                    curTimeText.textContent = RehatMediaManager.formatTime(targetTime);
                }
                RehatMediaManager.updateSliderFill(this, targetTime, this.max);
            });

            progressSlider.addEventListener('change', function () {
                videoElement.currentTime = parseFloat(this.value);
            });
        }

        videoElement.addEventListener('ended', function () {
            RehatMediaManager.setMediaUiPaused(mediaId, 'video');
            RehatMediaManager.currentMedia = null;
        });

        videoElement.addEventListener('error', function () {
            RehatMediaManager.handleMediaError(mediaId, 'video');
        });

        function toggleVideoPlay() {
            if (posterPlayBtn && posterPlayBtn.disabled) return;

            if (!videoElement.paused) {

                videoElement.pause();
                RehatMediaManager.setMediaUiPaused(mediaId, 'video');
                RehatMediaManager.currentMedia = null;
            } else {

                RehatMediaManager.pauseCurrentMedia();

                if (posterWrap) posterWrap.style.display = 'none';
                if (videoPlayerWrap) videoPlayerWrap.style.display = 'flex';

                videoElement.play().then(() => {
                    RehatMediaManager.currentMedia = {
                        type: 'video',
                        id: mediaId,
                        element: videoElement
                    };
                    RehatMediaManager.setMediaUiPlaying(mediaId, 'video');
                }).catch(err => {
                    RehatMediaManager.handleMediaError(mediaId, 'video');
                });
            }
        }

        if (posterPlayBtn) {
            posterPlayBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                toggleVideoPlay();
            });
        }

        if (ctrlPlayBtn) {
            ctrlPlayBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                toggleVideoPlay();
            });
        }

        videoElement.addEventListener('click', function (e) {
            e.preventDefault();
            toggleVideoPlay();
        });

        if (volBtn) {
            volBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                videoElement.muted = !videoElement.muted;
                const icon = volBtn.querySelector('.material-icons-round');
                if (icon) {
                    icon.textContent = videoElement.muted ? 'volume_off' : 'volume_up';
                }
            });
        }

        if (fullscreenBtn) {
            fullscreenBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                if (!document.fullscreenElement) {
                    if (videoPlayerWrap.requestFullscreen) {
                        videoPlayerWrap.requestFullscreen();
                    } else if (videoElement.requestFullscreen) {
                        videoElement.requestFullscreen();
                    } else if (videoElement.webkitEnterFullscreen) {
                        videoElement.webkitEnterFullscreen();
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }
            });
        }
    });

    const rehatTabs = document.querySelectorAll('.rehat-tab');
    rehatTabs.forEach(tab => {
        tab.addEventListener('click', function () {
            RehatMediaManager.pauseCurrentMedia();
        });
    });

    const sidebarLinks = document.querySelectorAll('.sidebar-nav__item');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function () {
            RehatMediaManager.pauseCurrentMedia();
        });
    });

     window.addEventListener('beforeunload', function () {
        RehatMediaManager.pauseCurrentMedia();
    });

    document.addEventListener('visibilitychange', function () {
        if (document.hidden) {
            RehatMediaManager.pauseCurrentMedia();
        }
    });
});
