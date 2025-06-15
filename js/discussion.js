document.addEventListener('DOMContentLoaded', () => {
    const messages = document.querySelectorAll('.message-block');

    messages.forEach(msg => {
        let timer = null;

        // Klik kanan (desktop)
        msg.addEventListener('contextmenu', function (e) {
            e.preventDefault();
            const postId = this.dataset.postId;
            window.location.href = `info_post.php?post_id=${postId}`;
        });

        // Tahan lama (HP)
        msg.addEventListener('touchstart', function () {
            timer = setTimeout(() => {
                const postId = this.dataset.postId;
                window.location.href = `info_post.php?post_id=${postId}`;
            }, 800);
        });

        msg.addEventListener('touchend', () => {
            if (timer) clearTimeout(timer);
        });
    });
});
