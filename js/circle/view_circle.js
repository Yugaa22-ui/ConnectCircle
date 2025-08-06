function initViewCircleSearch() {
  const form = document.querySelector('.search-form');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const query = form.querySelector('input[name="search"]').value;
    const resultsContainer = document.getElementById('circle-results');

    fetch(`../backend/circle/view_circle_ajax.php?search=${encodeURIComponent(query)}`)
      .then(res => res.text())
      .then(html => {
        resultsContainer.innerHTML = html;
      })
      .catch(() => {
        resultsContainer.innerHTML = `<div class="alert alert-danger">Gagal memuat data.</div>`;
      });
  });
}

// Auto-inisialisasi jika dipanggil langsung
if (typeof initViewCircleSearch === 'function') {
  initViewCircleSearch();
}

console.log("✅ view_circle.js berhasil dimuat");

function initCancelJoinRequest() {
  console.log("📌 initCancelJoinRequest() dipanggil");
  const container = document.getElementById('circle-results');
  if (!container) {
    console.warn("❌ container #circle-results tidak ditemukan");
    return;
  }

  container.addEventListener('submit', async function (e) {
    const form = e.target.closest('.cancel-request-form');
    if (!form) {
      console.warn("❌ form .cancel-request-form tidak ditemukan saat submit");
      return;
    }

    e.preventDefault();
    console.log("🚨 Form batalkan dikirim");

    const circleId = form.dataset.circleId;
    if (!circleId) {
      console.warn("❌ circle_id kosong");
      return;
    }

    try {
      const res = await fetch('../backend/circle/cancel_request.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ circle_id: circleId })
      });

      const result = await res.json();

      if (result.success) {
        console.log("✅ Permintaan berhasil dibatalkan");
        form.closest('.list-group-item').remove();
      } else {
        console.warn("❌ Gagal membatalkan:", result.error);
      }
    } catch (err) {
      console.error('🔥 Gagal koneksi:', err);
    }
  });
}

if (typeof initCancelJoinRequest === 'function') {
  initCancelJoinRequest();
}
