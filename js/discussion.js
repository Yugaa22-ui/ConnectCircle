document.addEventListener('DOMContentLoaded', () => {
    const messageContainer = document.getElementById('message-container');
    const imageInput = document.getElementById('imageInput');
    const previewImage = document.getElementById('preview-image');
    const previewContainer = document.getElementById('preview-image-container');
    const cancelWrapper = document.getElementById('cancel-image-wrapper');
    const uploadBtn = document.getElementById('uploadBtn');

    // Auto scroll ke bawah saat load
    if (messageContainer) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }

    // Klik icon upload gambar
    if (uploadBtn && imageInput) {
        uploadBtn.addEventListener('click', function (e) {
            e.preventDefault();
            imageInput.click();
        });
    }

    // Preview gambar & tombol batal
    if (imageInput) {
        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';

                    // Hapus tombol lama dulu
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

    // Load modal info pesan
    function loadInfoModal(postId) {
        const modalBody = document.getElementById('messageInfoContent');
        modalBody.innerHTML = '<div class="text-center text-muted">Memuat...</div>';

        fetch(`info_post.php?post_id=${postId}`)
            .then(res => res.text())
            .then(html => {
                modalBody.innerHTML = html;
                new bootstrap.Modal(document.getElementById('messageInfoModal')).show();
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

    // Edit pesan
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

    // Konfirmasi hapus
    document.querySelectorAll('[data-bs-target="#confirmDeleteModal"]').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('deletePostId').value = this.getAttribute('data-post-id');
        });
    });
});