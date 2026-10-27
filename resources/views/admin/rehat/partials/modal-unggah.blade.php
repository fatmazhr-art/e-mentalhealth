<div class="rehat-modal-backdrop {{ request()->query('modal') === 'unggah' ? 'is-open' : '' }}" id="modalUnggahMedia" role="dialog" aria-modal="true" aria-labelledby="modalUnggahTitle">
    <div class="rehat-modal">

        <div class="rehat-modal__header">
            <h2 class="rehat-modal__title" id="modalUnggahTitle">Unggah Media Baru</h2>
            <button type="button" class="rehat-modal__close" id="btnTutupModal" onclick="document.getElementById('modalUnggahMedia').classList.remove('is-open'); document.body.style.overflow = '';" aria-label="Tutup Modal">&times;</button>
        </div>
        <hr class="rehat-modal__divider">

        <div class="rehat-modal__body">

            <div class="rehat-form-group">
                <h3 class="rehat-format-title">Format Media</h3>
                <div class="rehat-format-grid">

                    <div class="rehat-format-card rehat-format-card--audio {{ $tab === 'audio' ? 'is-active' : '' }}" id="btnFormatAudio" role="button" tabindex="0">
                        <div class="rehat-format-card__header">
                            <span class="material-icons-round rehat-format-card__icon">graphic_eq</span>
                            <span class="rehat-format-card__name">Musik/Audio</span>
                        </div>
                        <p class="rehat-format-card__sub">.mp3, WAV, aac(Maks 50mb)</p>
                    </div>

                    <div class="rehat-format-card rehat-format-card--video {{ $tab === 'video' ? 'is-active' : '' }}" id="btnFormatVideo" role="button" tabindex="0">
                        <div class="rehat-format-card__header">
                            <span class="material-icons-round rehat-format-card__icon">smart_display</span>
                            <span class="rehat-format-card__name">Video</span>
                        </div>
                        <p class="rehat-format-card__sub">Tautakan video youtube</p>
                    </div>

                    <div class="rehat-format-card rehat-format-card--article {{ $tab === 'artikel' || empty($tab) ? 'is-active' : '' }}" id="btnFormatArtikel" role="button" tabindex="0">
                        <div class="rehat-format-card__header">
                            <span class="material-icons-round rehat-format-card__icon">post_add</span>
                            <span class="rehat-format-card__name">Artikel & Tips</span>
                        </div>
                        <p class="rehat-format-card__sub">Tautakan artikel & tips</p>
                    </div>
                </div>
            </div>

            <div class="rehat-form-section" id="formSectionVideo" style="{{ $tab === 'video' ? 'display: block;' : 'display: none;' }}">

                <div class="rehat-form-group">
                    <label for="inputUrlVideo" class="rehat-form-label">URL TAUTAN YOUTUBE</label>
                    <input type="text"
                           id="inputUrlVideo"
                           name="url_video"
                           class="rehat-form-input"
                           value="{{ $formVideo['url'] ?? 'https://www.google search?q=mengatasi+burnout+akademik+skripsi' }}">
                </div>

                <div class="rehat-preview-box">
                    <span class="rehat-preview-box__label">PRATINJAU</span>
                    <div class="rehat-preview-box__content">
                        <div class="rehat-preview-box__thumb-wrapper">
                            @if(file_exists(public_path('images/admin/rehat/video-1.jpg')))
                                <img src="{{ asset('images/admin/rehat/video-1.jpg') }}?v={{ filemtime(public_path('images/admin/rehat/video-1.jpg')) }}"
                                     alt="Pratinjau Video"
                                     class="rehat-preview-box__thumb">
                            @else
                                <div class="rehat-preview-box__thumb-placeholder"></div>
                            @endif
                        </div>
                        <div class="rehat-preview-box__meta">
                            <a href="#" class="rehat-preview-box__domain">{{ $formVideo['pratinjau']['domain'] ?? 'google.com/ayosehat.kemkes.go.if' }}</a>
                            <h4 class="rehat-preview-box__title">{{ $formVideo['pratinjau']['judul'] ?? 'Praktik Grounding 5-4-3-2-1 Saat Mengalami Panic' }}</h4>
                            <p class="rehat-preview-box__desc">{{ $formVideo['pratinjau']['deskripsi'] ?? 'Panduan kognitif behavioral berbasis bukti untuk mengenali bias pemikiran saat menghadapi pertanyaan dosen penguji.' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rehat-form-group">
                    <label for="inputJudulVideo" class="rehat-form-label">JUDUL ARTIKEL</label>
                    <input type="text"
                           id="inputJudulVideo"
                           name="judul_video"
                           class="rehat-form-input"
                           value="{{ $formVideo['judul'] ?? '' }}">
                </div>

                <div class="rehat-form-group">
                    <label class="rehat-form-label rehat-form-label--case">Kategori & Topik masalah</label>
                    <div class="rehat-tags-row" id="rehatTagsRowVideo">
                        @php
                            $videoTags = $formVideo['kategori'] ?? ['Healing', 'tugas akhir', 'Kecemasan karir'];
                        @endphp
                        @foreach($videoTags as $tag)
                            <div class="rehat-tag-pill"
                                 onclick="this.classList.toggle('is-inactive')"
                                 role="button"
                                 tabindex="0"
                                 title="Klik untuk memilih/membatalkan, klik dua kali untuk ubah teks">
                                <span class="rehat-tag-pill__text" ondblclick="event.stopPropagation(); let t = prompt('Ubah nama topik/kategori:', this.textContent.trim()); if(t) this.textContent = t.trim();">{{ $tag }}</span>
                                <button type="button" class="rehat-tag-pill__remove" onclick="event.stopPropagation(); this.parentElement.remove()" aria-label="Hapus tag">&times;</button>
                            </div>
                        @endforeach
                        <button type="button" class="rehat-tag-pill rehat-tag-pill--add" id="btnTambahTagVideo" onclick="tambahTagInline(this)">
                            <span>Tambah +</span>
                        </button>
                    </div>
                </div>

                <div class="rehat-form-group">
                    <label for="inputDeskripsiVideo" class="rehat-form-label">DESKRIPSI</label>
                    <textarea id="inputDeskripsiVideo"
                              name="deskripsi_video"
                              rows="3"
                              class="rehat-form-textarea">{{ $formVideo['deskripsi'] ?? '' }}</textarea>
                </div>

                <div class="rehat-modal__actions">
                    <button type="button" class="rehat-btn-submit rehat-btn-submit--draf" onclick="alert('Video disimpan sebagai DRAF!')">
                        DRAF
                    </button>
                    <button type="button" class="rehat-btn-submit rehat-btn-submit--terbitkan" onclick="alert('Video berhasil DITERBITKAN!')">
                        TERBITKAN
                    </button>
                </div>
            </div>

            <div class="rehat-form-section" id="formSectionAudio" style="{{ $tab === 'audio' ? 'display: block;' : 'display: none;' }}">

                <div class="rehat-dropzone" onclick="document.getElementById('inputAudioFile').click()">
                    <input type="file" id="inputAudioFile" accept=".mp3,.wav,.aac" style="display: none;">
                    <div class="rehat-dropzone__icon-wrap">
                        <span class="material-icons-round rehat-dropzone__icon">upload</span>
                    </div>
                    <p class="rehat-dropzone__text">
                        Tarik & lepas berkas audio di sini, atau <a href="javascript:void(0)" class="rehat-dropzone__link">Jelajahi berkas</a>
                    </p>
                </div>

                <div class="rehat-form-group">
                    <label for="inputJudulAudio" class="rehat-form-label">JUDUL MUSIK/AUDIO</label>
                    <input type="text"
                           id="inputJudulAudio"
                           name="judul_audio"
                           class="rehat-form-input"
                           value="{{ $formAudio['judul'] ?? 'Please please please' }}">
                </div>

                <div class="rehat-form-group">
                    <label for="inputDeskripsiAudio" class="rehat-form-label">DESKRIPSI</label>
                    <textarea id="inputDeskripsiAudio"
                              name="deskripsi_audio"
                              rows="3"
                              class="rehat-form-textarea">{{ $formAudio['deskripsi'] ?? 'lagu nya enak relate sama etmin lagu nya enak relate sama etmin musik nya gaul pokoknua bagusmusik nya gaul pokoknua bagus lagu nya enak relate sama etmin musik nya gaul pokoknua bagus' }}</textarea>
                </div>

                <div class="rehat-form-row-2col">
                    <div class="rehat-form-group">
                        <label for="inputPengisiSuara" class="rehat-form-label">PENGISI SUARA</label>
                        <input type="text"
                               id="inputPengisiSuara"
                               name="pengisi_suara"
                               class="rehat-form-input"
                               value="{{ $formAudio['pengisiSuara'] ?? 'Sabrina Carpenter' }}">
                    </div>
                    <div class="rehat-form-group">
                        <label for="inputDurasiAudio" class="rehat-form-label">DURASI</label>
                        <input type="text"
                               id="inputDurasiAudio"
                               name="durasi_audio"
                               class="rehat-form-input"
                               value="{{ $formAudio['durasi'] ?? '03.33' }}">
                    </div>
                </div>

                <div class="rehat-modal__actions">
                    <button type="button" class="rehat-btn-submit rehat-btn-submit--draf" onclick="alert('Audio disimpan sebagai DRAF!')">
                        DRAF
                    </button>
                    <button type="button" class="rehat-btn-submit rehat-btn-submit--terbitkan" onclick="alert('Audio berhasil DITERBITKAN!')">
                        TERBITKAN
                    </button>
                </div>
            </div>

            <div class="rehat-form-section" id="formSectionArtikel" style="{{ $tab === 'artikel' || empty($tab) ? 'display: block;' : 'display: none;' }}">

                <div class="rehat-form-group">
                    <label for="inputUrlArtikel" class="rehat-form-label">URL TAUTAN ARTIKEL (GOOGLE/SUMBER LUAR)</label>
                    <input type="text"
                           id="inputUrlArtikel"
                           name="url"
                           class="rehat-form-input"
                           value="{{ $formArtikel['url'] ?? 'https://www.google search?q=mengatasi+burnout+akademik+skripsi' }}">
                </div>

                <div class="rehat-preview-box">
                    <span class="rehat-preview-box__label">PRATINJAU</span>
                    <div class="rehat-preview-box__content">
                        <div class="rehat-preview-box__thumb-wrapper">
                            @if(file_exists(public_path('images/admin/rehat/artikel-1.jpg')))
                                <img src="{{ asset('images/admin/rehat/artikel-1.jpg') }}?v={{ filemtime(public_path('images/admin/rehat/artikel-1.jpg')) }}"
                                     alt="Pratinjau Artikel"
                                     class="rehat-preview-box__thumb">
                            @else
                                <div class="rehat-preview-box__thumb-placeholder"></div>
                            @endif
                        </div>
                        <div class="rehat-preview-box__meta">
                            <a href="#" class="rehat-preview-box__domain">{{ $formArtikel['pratinjau']['domain'] ?? $pratinjau['domain'] ?? 'google.com/ayosehat.kemkes.go.if' }}</a>
                            <h4 class="rehat-preview-box__title">{{ $formArtikel['pratinjau']['judul'] ?? $pratinjau['judul'] ?? 'Megenal Sindrom Imposter Ditengah Tekanan Seminar...' }}</h4>
                            <p class="rehat-preview-box__desc">{{ $formArtikel['pratinjau']['deskripsi'] ?? $pratinjau['deskripsi'] ?? 'Panduan kognitif behavioral berbasis bukti untuk mengenali bias pemikiran saat menghadapi pertanyaan dosen penguji.' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rehat-form-group">
                    <label for="inputJudulArtikel" class="rehat-form-label">JUDUL ARTIKEL</label>
                    <input type="text"
                           id="inputJudulArtikel"
                           name="judul"
                           class="rehat-form-input"
                           value="{{ $formArtikel['judul'] ?? 'Mengenal sindrom imposter....' }}">
                </div>

                <div class="rehat-form-group">
                    <label class="rehat-form-label rehat-form-label--case">Kategori & Topik masalah</label>
                    <div class="rehat-tags-row" id="rehatTagsRow">
                        @php
                            $tags = $formArtikel['kategori'] ?? $kategoriContoh ?? ['Healing', 'tugas akhir', 'Kecemasan karir'];
                        @endphp
                        @foreach($tags as $tag)
                            <div class="rehat-tag-pill"
                                 onclick="this.classList.toggle('is-inactive')"
                                 role="button"
                                 tabindex="0"
                                 title="Klik untuk memilih/membatalkan, klik dua kali untuk ubah teks">
                                <span class="rehat-tag-pill__text" ondblclick="event.stopPropagation(); let t = prompt('Ubah nama topik/kategori:', this.textContent.trim()); if(t) this.textContent = t.trim();">{{ $tag }}</span>
                                <button type="button" class="rehat-tag-pill__remove" onclick="event.stopPropagation(); this.parentElement.remove()" aria-label="Hapus tag">&times;</button>
                            </div>
                        @endforeach
                        <button type="button" class="rehat-tag-pill rehat-tag-pill--add" id="btnTambahTag" onclick="tambahTagInline(this)">
                            <span>Tambah +</span>
                        </button>
                    </div>
                </div>

                <div class="rehat-form-group">
                    <label for="inputDeskripsiArtikel" class="rehat-form-label">DESKRIPSI</label>
                    <textarea id="inputDeskripsiArtikel"
                              name="deskripsi"
                              rows="3"
                              class="rehat-form-textarea">{{ $formArtikel['deskripsi'] ?? 'Panduan kognitif behavioral berbasis bukti untuk mengenali bias pemikiran saat menghadapi pertanyaan dosen penguji.' }}</textarea>
                </div>

                <div class="rehat-modal__actions">
                    <button type="button" class="rehat-btn-submit rehat-btn-submit--draf" onclick="alert('Artikel berhasil disimpan sebagai DRAF!')">
                        DRAF
                    </button>
                    <button type="button" class="rehat-btn-submit rehat-btn-submit--terbitkan" onclick="alert('Artikel berhasil DITERBITKAN!')">
                        TERBITKAN
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
