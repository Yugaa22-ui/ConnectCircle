function sendFriendRequest(targetId) {
    const btnContainer = document.querySelector(`#friend-btn-${targetId}`);
    const original = btnContainer.innerHTML;

    // Tampilkan spinner sementara
    btnContainer.innerHTML = '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>';

    fetch('../backend/friend/send_friend_request.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'target_user=' + encodeURIComponent(targetId)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'ok') {
            btnContainer.innerHTML = '<span class="badge bg-success">Permintaan dikirim</span>';
        } else if (data.status === 'already_friends') {
            btnContainer.innerHTML = '<span class="badge bg-success">Sudah berteman</span>';
        } else if (data.status === 'already_sent') {
            btnContainer.innerHTML = '<span class="badge bg-warning text-dark">Menunggu konfirmasi</span>';
        } else {
            btnContainer.innerHTML = '<span class="badge bg-danger">Gagal mengirim</span>';
            setTimeout(() => {
                btnContainer.innerHTML = original;
            }, 2500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btnContainer.innerHTML = '<span class="badge bg-danger">Terjadi kesalahan</span>';
        setTimeout(() => {
            btnContainer.innerHTML = original;
        }, 2500);
    });
}
