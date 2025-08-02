document.addEventListener('DOMContentLoaded', () => {
    const messageContainer = document.getElementById('message-container');
    const imageInput = document.getElementById('imageInput');
    const mediaInput = document.getElementById('mediaInput');
    const previewImage = document.getElementById('preview-image');
    const previewContainer = document.getElementById('preview-image-container');
    const cancelWrapper = document.getElementById('cancel-image-wrapper');
    const uploadImageBtn = document.getElementById('uploadImageBtn');
    const uploadMediaBtn = document.getElementById('uploadMediaBtn');
    const previewMediaContainer = document.getElementById('preview-media-container');
    const previewMediaWrapper = document.getElementById('preview-media-wrapper');

    if (messageContainer) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }

    if (uploadImageBtn && imageInput) {
        uploadImageBtn.addEventListener('click', e => {
            e.preventDefault();
            imageInput.click();
        });
    }
    if (uploadMediaBtn && mediaInput) {
        uploadMediaBtn.addEventListener('click', e => {
            e.preventDefault();
            mediaInput.click();
        });
    }

    if (imageInput) {
        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                    const oldBtn = document.getElementById('cancelImageBtn');
                    if (oldBtn) oldBtn.remove();

                    const cancelBtn = document.createElement('button');
                    cancelBtn.className = 'btn btn-sm btn-outline-danger mt-2';
                    cancelBtn.id = 'cancelImageBtn';
                    cancelBtn.textContent = 'Batalkan Gambar';
                    cancelBtn.onclick = () => {
                        imageInput.value = '';
                        previewImage.src = '';
                        previewContainer.style.display = 'none';
                        cancelBtn.remove();
                    };
                    cancelWrapper?.appendChild(cancelBtn);
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        });
    }

if (mediaInput) {
    mediaInput.addEventListener('change', function () {
        document.getElementById('mediaDropdownMenu').style.display = 'none';
        const file = this.files[0];
        previewMediaWrapper.innerHTML = '';
        if (!file) {
            previewMediaContainer.style.display = 'none';
            return;
        }
        const url = URL.createObjectURL(file);
        const isVideo = file.type.startsWith('video');
        const isAudio = file.type.startsWith('audio');

        let previewEl;
        if (isVideo) {
            previewEl = document.createElement('video');
            previewEl.src = url;
            previewEl.controls = true;
            previewEl.width = 200;
            previewEl.className = 'rounded border';
        } else if (isAudio) {
            previewEl = document.createElement('audio');
            previewEl.src = url;
            previewEl.controls = true;
            previewEl.className = 'w-100 mt-2';
        } else {
            previewEl = document.createElement('small');
            previewEl.className = 'text-muted';
            previewEl.textContent = 'Format tidak didukung untuk preview.';
        }

        previewMediaWrapper.appendChild(previewEl);

        // Tombol batal
        const cancelBtn = document.createElement('button');
        cancelBtn.className = 'btn btn-sm btn-outline-danger ms-2 mt-2';
        cancelBtn.textContent = 'Batalkan Media';
        cancelBtn.onclick = () => {
            mediaInput.value = '';
            previewMediaWrapper.innerHTML = '';
            previewMediaContainer.style.display = 'none';
        };
        previewMediaWrapper.appendChild(cancelBtn);

        previewMediaContainer.style.display = 'block';
    });
}

    // === VOICE NOTE ===
    let mediaRecorder, audioChunks = [], recordingInterval;
    const voiceWrapper = document.getElementById('voiceRecordingWrapper');
    const recordingStatus = document.getElementById('recordingStatus');
    const recordingTime = document.getElementById('recordingTime');
    const cancelBtn = document.getElementById('cancelRecordingBtn');
    const sendBtn = document.getElementById('sendRecordingBtn');
    const previewAudio = document.getElementById('recordingPreview');
    const voiceBlobInput = document.getElementById('voiceBlobInput');
    const stopBtn = document.getElementById('stopRecordingBtn');

    function startTimer() {
        let seconds = 0;
        recordingInterval = setInterval(() => {
            seconds++;
            const min = Math.floor(seconds / 60);
            const sec = seconds % 60;
            recordingTime.textContent = `${min}:${sec < 10 ? '0' : ''}${sec}`;
        }, 1000);
    }
    function stopTimer() {
        clearInterval(recordingInterval);
        recordingTime.textContent = '0:00';
    }

    function startVoiceRecording() {
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {
                audioChunks = [];
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.ondataavailable = e => {
                    if (e.data.size > 0) audioChunks.push(e.data);
                };
                mediaRecorder.onstop = () => {
                    const blob = new Blob(audioChunks, { type: 'audio/webm' });
                    const url = URL.createObjectURL(blob);
                    previewAudio.src = url;
                    previewAudio.style.display = 'block';
                    blobToBase64(blob, base64 => voiceBlobInput.value = base64);
                    stopBtn.style.display = 'none';
                    sendBtn.style.display = 'inline-block';
                    cancelBtn.style.display = 'inline-block';
                };
                mediaRecorder.start();
                voiceWrapper.style.display = 'block';
                stopBtn.style.display = 'inline-block';
                sendBtn.style.display = 'none';
                cancelBtn.style.display = 'none';
                startTimer();
            })
            .catch(err => alert("Tidak bisa mengakses mikrofon: " + err.message));
    }

    stopBtn?.addEventListener('click', () => {
        if (mediaRecorder?.state === 'recording') {
            mediaRecorder.stop();
            stopTimer();
        }
    });

    cancelBtn?.addEventListener('click', () => {
        if (mediaRecorder?.state === 'recording') mediaRecorder.stop();
        stopTimer();
        voiceWrapper.style.display = 'none';
        previewAudio.src = '';
        voiceBlobInput.value = '';
    });

    function blobToBase64(blob, callback) {
        const reader = new FileReader();
        reader.onloadend = () => callback(reader.result.split(',')[1]);
        reader.readAsDataURL(blob);
    }

    const micBtn = document.getElementById('micBtn');
    if (micBtn) {
        const isMobile = window.innerWidth <= 768;
        let micTimeout;
        if (isMobile) {
            micBtn.addEventListener('touchstart', () => {
                micTimeout = setTimeout(() => startVoiceRecording(), 1000);
            });
            micBtn.addEventListener('touchend', () => {
                if (micTimeout) clearTimeout(micTimeout);
            });
        } else {
            micBtn.addEventListener('click', () => startVoiceRecording());
        }
    }

    // Fix modal aria-hidden cleanup
    const infoModal = document.getElementById('messageInfoModal');
    if (infoModal) {
        infoModal.addEventListener('hidden.bs.modal', () => {
            infoModal.removeAttribute('aria-hidden');
            infoModal.blur();
        });
    }

    function loadInfoModal(postId) {
        const modalBody = document.getElementById('messageInfoContent');
        const modalEl = document.getElementById('messageInfoModal');
        modalBody.innerHTML = '<div class="text-center text-muted">Memuat...</div>';

        const existing = bootstrap.Modal.getInstance(modalEl);
        if (existing) existing.hide();

        fetch(`info_post.php?post_id=${postId}`)
            .then(res => res.text())
            .then(html => {
                modalBody.innerHTML = html;
                new bootstrap.Modal(modalEl).show();
            })
            .catch(() => {
                modalBody.innerHTML = '<div class="text-danger">Gagal memuat info.</div>';
            });
    }

    document.querySelectorAll('.btn-info-post').forEach(btn => {
        btn.addEventListener('click', () => loadInfoModal(btn.dataset.postId));
    });

    document.querySelectorAll('.message-block').forEach(msg => {
        let timeoutId;
        const postId = msg.dataset.postId;

        msg.addEventListener('contextmenu', function (e) {
            e.preventDefault();
            loadInfoModal(postId);
        });

        msg.addEventListener('touchstart', () => {
            timeoutId = setTimeout(() => loadInfoModal(postId), 800);
        });

        msg.addEventListener('touchend', () => {
            if (timeoutId) clearTimeout(timeoutId);
        });
    });

    document.querySelectorAll('.btn-edit-post').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('editPostId').value = this.dataset.id;
            document.getElementById('editContent').value = this.dataset.content;
            document.getElementById('editSnackbar').textContent = '';
            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    });

    const editForm = document.getElementById('editPostForm');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const postId = document.getElementById('editPostId').value;
            const content = document.getElementById('editContent').value.trim();
            const snackbar = document.getElementById('editSnackbar');

            if (content === '') {
                snackbar.classList.remove('text-success');
                snackbar.classList.add('text-danger');
                snackbar.textContent = 'Isi pesan tidak boleh kosong.';
                return;
            }

            fetch('../backend/circle/edit_post.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `post_id=${encodeURIComponent(postId)}&new_content=${encodeURIComponent(content)}`
            })
            .then(res => res.json())
            .then(data => {
                snackbar.classList.remove('text-danger', 'text-success');
                if (data.status === 'success') {
                    snackbar.classList.add('text-success');
                    snackbar.textContent = 'Pesan berhasil diperbarui. Memuat ulang...';
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    snackbar.classList.add('text-danger');
                    snackbar.textContent = 'Gagal memperbarui pesan.';
                }
            })
            .catch(() => {
                snackbar.classList.add('text-danger');
                snackbar.textContent = 'Terjadi kesalahan.';
            });
        });
    }

    document.querySelectorAll('[data-bs-target="#confirmDeleteModal"]').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('deletePostId').value = this.getAttribute('data-post-id');
        });
    });
});

document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(btn => {
  new bootstrap.Dropdown(btn);
});
