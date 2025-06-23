function initJoinCircleButtons() {
  document.querySelectorAll('.join-btn').forEach(button => {
    button.addEventListener('click', async function () {
      const id = this.dataset.circleId;
      const isPrivate = this.dataset.isPrivate === '1';
      const confirmMsg = isPrivate
        ? 'Yakin ingin mengajukan permintaan ke circle ini?'
        : 'Yakin ingin bergabung ke circle ini?';
      if (!confirm(confirmMsg)) return;

      try {
        const res = await fetch(`../backend/circle/join_circle_process.php`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ circle_id: id, is_private: isPrivate ? 1 : 0 })
        });        

        const result = await res.json();
        showSnackbar(result.success || result.error, result.success ? 'success' : 'danger');

        if (result.success) {
          this.disabled = true;
          this.textContent = isPrivate ? 'Menunggu Persetujuan' : 'Tergabung';
        }
      } catch (err) {
        showSnackbar('Terjadi kesalahan saat mengirim permintaan.', 'danger');
      }
    });
  });
}

function showSnackbar(msg, type) {
  const container = document.getElementById('snackbar-container');
  container.innerHTML = `
    <div class="alert alert-${type} shadow fade show text-center" role="alert">
      ${msg}
    </div>
  `;
  setTimeout(() => { container.innerHTML = ''; }, 3000);
}

// ✅ Panggil langsung jika tidak dimuat melalui fetch
if (typeof initJoinCircleButtons === 'function') {
  initJoinCircleButtons();
}
