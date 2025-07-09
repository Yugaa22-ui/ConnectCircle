window.initFriendListHandler = function () {
    console.log("✅ initFriendListHandler() dipanggil");
  
    let selectedUserId = null;
    let selectedUserName = null;
  
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmUnfriendModal'));
    const confirmBtn = document.getElementById('confirmUnfriendBtn');
    const modalBody = document.getElementById('confirmUnfriendBody');
  
    document.querySelectorAll('.unfriend-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        selectedUserId = this.getAttribute('data-user-id');
        selectedUserName = this.getAttribute('data-username');
  
        modalBody.textContent = `Anda yakin ingin menghapus ${selectedUserName} dari daftar teman?`;
        confirmModal.show();
      });
    });
  
    confirmBtn.addEventListener('click', function() {
      if (!selectedUserId) return;
  
      confirmBtn.disabled = true;
      confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menghapus...';
  
      fetch('../backend/friend/delete_friend.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'friend_id=' + encodeURIComponent(selectedUserId)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'ok') {
          // Hapus item dari DOM
          const item = document.querySelector(`.list-group-item[data-user-id="${selectedUserId}"]`);
          if (item) item.remove();
  
          confirmModal.hide();
        } else {
          alert('Gagal menghapus teman.');
        }
      })
      .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan.');
      })
      .finally(() => {
        confirmBtn.disabled = false;
        confirmBtn.textContent = 'Konfirmasi';
        selectedUserId = null;
      });
    });
  };
  