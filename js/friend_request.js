function initFriendRequestHandler() {
    console.log("✅ initFriendRequestHandler dipanggil");
  
    const cardBody = document.querySelector('.card-body');
    if (!cardBody) {
      console.warn("⚠️ .card-body tidak ditemukan");
      return;
    }
  
    const alertContainer = document.createElement('div');
    alertContainer.className = 'mt-2';
    cardBody.prepend(alertContainer);
  
    cardBody.addEventListener('click', function (e) {
      const btn = e.target.closest('button');
      if (!btn || !btn.form || !btn.form.matches('[data-request-form]')) return;
  
      e.preventDefault();
  
      const form = btn.form;
      const requestId = form.querySelector('input[name="request_id"]')?.value;
      const action = btn.value;
  
      console.log("📨 Tombol diklik:", btn);
      console.log("🆔 requestId =", requestId, "| action =", action);
  
      const originalHtml = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status"></span>`;
  
      fetch('../backend/friend/friend_request_action.php', {
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
  
            const list = document.querySelector('.list-group');
            if (!list || list.children.length === 0) {
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
          console.error("❌ Fetch error:", err);
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
  
  window.initFriendRequestHandler = initFriendRequestHandler;
  console.log("📌 window.initFriendRequestHandler sekarang sudah tersedia.");
  