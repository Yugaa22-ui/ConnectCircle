document.addEventListener('DOMContentLoaded', () => {
    const messageContainer = document.getElementById('message-container');

    // Tombol Info Pesan
    document.querySelectorAll('.btn-info-post').forEach(btn => {
        btn.addEventListener('click', function () {
            const postId = this.dataset.postId;
            loadInfoModal(postId);
        });
    });

    // Klik kanan & Tahan lama (info)
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

    // Fungsi load modal info
    function loadInfoModal(postId) {
        const modalBody = document.getElementById('messageInfoContent');
        modalBody.innerHTML = '<div class="text-center text-muted">Memuat...</div>';

        fetch(`../backend/circle/info_post.php?post_id=${postId}`)
            .then(res => res.text())
            .then(html => {
                modalBody.innerHTML = html;
                new bootstrap.Modal(document.getElementById('messageInfoModal')).show();
            })
            .catch(() => {
                modalBody.innerHTML = '<div class="text-danger">Gagal memuat info.</div>';
            });
    }

    // Tombol Edit
    document.querySelectorAll('.btn-edit-post').forEach(btn => {
        btn.addEventListener('click', function () {
            const postId = this.dataset.id;
            const content = this.dataset.content;

            document.getElementById('editPostId').value = postId;
            document.getElementById('editContent').value = content;
            document.getElementById('editSnackbar').textContent = '';

            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    });

    // Submit Edit
    const editForm = document.getElementById('editPostForm');
    editForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const postId = document.getElementById('editPostId').value;
        const content = document.getElementById('editContent').value.trim();
        const snackbar = document.getElementById('editSnackbar');

        if (content === '') {
            snackbar.textContent = 'Isi pesan tidak boleh kosong.';
            return;
        }

        fetch('../backend/circle/edit_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `post_id=${encodeURIComponent(postId)}&new_content=${encodeURIComponent(content)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                snackbar.classList.remove('text-danger');
                snackbar.classList.add('text-success');
                snackbar.textContent = 'Pesan berhasil diperbarui. Memuat ulang...';
                setTimeout(() => window.location.reload(), 1000);
            } else if (data.status === 'unauthorized') {
                snackbar.textContent = 'Tidak diizinkan mengedit pesan ini.';
            } else if (data.status === 'invalid') {
                snackbar.textContent = 'Input tidak valid.';
            } else {
                snackbar.textContent = 'Gagal memperbarui pesan.';
            }
        })
        .catch(() => {
            snackbar.textContent = 'Terjadi kesalahan.';
        });
    });
});
