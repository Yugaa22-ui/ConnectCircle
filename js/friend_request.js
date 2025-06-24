function initFriendRequestHandler() {
    console.log("✅ initFriendRequestHandler dipanggil");
  
    const list = document.querySelector('.list-group');
    const cardBody = document.querySelector('.card-body');
    const formSelector = 'form[data-request-form]';
  
    if (!list || !cardBody) {
      console.warn("⚠️ Elemen list atau cardBody tidak ditemukan, akan dicoba ulang dalam 100ms...");
      setTimeout(initFriendRequestHandler, 100); // coba ulang
      return;
    }
  
    const alertContainer = document.createElement('div');
    alertContainer.className = 'mt-2';
    cardBody.prepend(alertContainer);
  
    // ✅ Event listener dipasang setelah dipastikan DOM sudah siap
    document.addEventListener('submit', function (e) {
    const form = e.target.closest('form[data-request-form]');
    if (!form) return;      
    console.log("📨 Form submit terdeteksi:", form);
      e.preventDefault();
  
      const formData = new FormData(form);
      const requestId = formData.get('request_id');
      const action = formData.get('action');
  
      const btn = form.querySelector(`button[value="${action}"]`);
      if (!btn) {
        console.error("❌ Tombol tidak ditemukan. Action:", action, form);
        return;
      }
      const originalHtml = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status"></span>`;
      
  
      fetch('../backend/friend/friend_request_process.php', {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
          request_id: requestId,
          action: action
        })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
  
        if (data.status === 'ok') {
          const item = form.closest('.list-group-item');
          if (item) item.remove();
  
          showAlert('success', data.message);
  
          if (list.children.length === 0) {
            list.remove();
            const emptyAlert = document.createElement('div');
            emptyAlert.className = 'alert alert-info';
            emptyAlert.textContent = 'Tidak ada permintaan pertemanan saat ini.';
            cardBody.appendChild(emptyAlert);
          }
        } else {
          showAlert('danger', data.message || 'Gagal memproses permintaan.');
        }
      })
      .catch(err => {
        console.error(err);
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        showAlert('danger', 'Terjadi kesalahan jaringan.');
      });
    });
  
    function showAlert(type, message) {
      alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
          ${message}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `;
    }
  }
  
// Ekspos ke global agar bisa dipanggil dari dashboard_user.js
window.initFriendRequestHandler = initFriendRequestHandler;
console.log("📌 window.initFriendRequestHandler sekarang sudah tersedia.");