// Fungsi: Kirim permintaan pertemanan
function sendFriendRequest(targetId) {
  const btnContainer = document.querySelector(`#friend-btn-${targetId}`);
  const original = btnContainer.innerHTML;

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

// Ekspos fungsi
window.initSearchFriendForm = function () {
  console.log("✅ initSearchFriendForm dipanggil");

  const form = document.querySelector('#search-form');
  const resultContainer = document.querySelector('#search-result');
  const input = form.querySelector('[name="minat"]');
  const radios = form.querySelectorAll('.interest-radio');
  const labels = form.querySelectorAll('#interest-tags label');

  if (!form || !resultContainer) {
    console.warn('⛔ Form atau hasil tidak ditemukan');
    return;
  }

  // Klik label radio agar mengisi input minat
  radios.forEach(radio => {
    radio.addEventListener('change', function() {
      input.value = this.value;
    });
  });

  // Saat input manual, hilangkan selection radio
  input.addEventListener('input', function() {
    radios.forEach(radio => radio.checked = false);
  });

  // Submit form
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const keyword = input.value.trim();

    if (!keyword) {
      resultContainer.innerHTML = '<div class="alert alert-warning">Masukkan kata kunci minat.</div>';
      return;
    }

    if (/[,\.]/.test(keyword) || /\s{2,}/.test(keyword)) {
      resultContainer.innerHTML = '<div class="alert alert-warning">Hanya boleh 1 minat.</div>';
      return;
    }

    resultContainer.innerHTML = '<div class="text-center my-3 text-muted">Sedang mencari...</div>';

    fetch(`../search/search_friend_embed.php?minat=${encodeURIComponent(keyword)}`)
      .then(res => res.text())
      .then(html => {
        resultContainer.innerHTML = html;
      })
      .catch(err => {
        console.error(err);
        resultContainer.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat mencari teman.</div>';
      });
  });
};
