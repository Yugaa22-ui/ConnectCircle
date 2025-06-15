document.addEventListener('DOMContentLoaded', () => {
    const contextMenu = document.getElementById('contextMenu');
    const messageContainer = document.getElementById('message-container');

    // Event tombol info
    document.querySelectorAll('.btn-info-post').forEach(btn => {
        btn.addEventListener('click', function () {
            const postId = this.dataset.postId;
            const modalBody = document.getElementById('messageInfoContent');
            modalBody.innerHTML = '<div class="text-center text-muted">Memuat...</div>';

            // Fetch data info post
            fetch(`../backend/circle/info_post.php?post_id=${postId}`)
                .then(res => res.text())
                .then(html => {
                    modalBody.innerHTML = html;
                    new bootstrap.Modal(document.getElementById('messageInfoModal')).show();
                })
                .catch(() => {
                    modalBody.innerHTML = '<div class="text-danger">Gagal memuat info.</div>';
                });
        });
    });

    // Klik kanan atau tahan lama (info kontekstual)
    document.querySelectorAll('.message-block').forEach(msg => {
        let timeoutId;
        const postId = msg.dataset.postId;

        // Klik kanan (desktop)
        msg.addEventListener('contextmenu', function (e) {
            e.preventDefault();
            showInfoModal(postId);
        });

        // Tahan lama (mobile)
        msg.addEventListener('touchstart', function () {
            timeoutId = setTimeout(() => showInfoModal(postId), 800);
        });

        msg.addEventListener('touchend', () => {
            if (timeoutId) clearTimeout(timeoutId);
        });

        function showInfoModal(id) {
            const modalBody = document.getElementById('messageInfoContent');
            modalBody.innerHTML = '<div class="text-center text-muted">Memuat...</div>';
            fetch(`../backend/circle/info_post.php?post_id=${id}`)
                .then(res => res.text())
                .then(html => {
                    modalBody.innerHTML = html;
                    new bootstrap.Modal(document.getElementById('messageInfoModal')).show();
                })
                .catch(() => {
                    modalBody.innerHTML = '<div class="text-danger">Gagal memuat info.</div>';
                });
        }
    });
});
