console.log("✅ friend_list.js DIMUAT");

window.initFriendListHandler = function () {
    console.log("✅ initFriendListHandler() dipanggil");
  
    const confirmModalEl = document.getElementById('confirmRemoveModal');
    const confirmBtn = document.getElementById('confirmRemoveBtn');
    const friendNameEl = document.getElementById('friend-name');
    let selectedUserId = null;
  
    // Tombol hapus
    document.querySelectorAll('.btn-remove-friend').forEach(btn => {
      btn.addEventListener('click', () => {
        selectedUserId = btn.dataset.userId;
        friendNameEl.textContent = btn.dataset.userName;
  
        // Munculkan modal secara manual
        const modalInstance = new bootstrap.Modal(confirmModalEl);
        modalInstance.show();
      });
    });
  
    // Tombol konfirmasi
    confirmBtn.addEventListener('click', () => {
      if (!selectedUserId) return;
  
      confirmBtn.disabled = true;
      confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menghapus...';
  
      fetch('../backend/friend/remove_friend.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'friend_id=' + encodeURIComponent(selectedUserId)
      })
        .then(r => r.json())
        .then(data => {
          if (data.status === 'ok') {
            const item = document.getElementById('friend-item-' + selectedUserId);
            if (item) item.remove();
  
            // Tutup modal
            const modalInstance = bootstrap.Modal.getInstance(confirmModalEl);
            modalInstance.hide();
          } else {
            alert('Gagal menghapus teman.');
          }
        })
        .catch(() => alert('Terjadi kesalahan.'))
        .finally(() => {
          confirmBtn.disabled = false;
          confirmBtn.textContent = 'Konfirmasi';
          selectedUserId = null;
        });
    });
  };
  