function initJoinCircleButtons() {
  document.querySelectorAll('.join-btn:not(.processed)').forEach(button => {
    button.classList.add('processed');

    button.addEventListener('click', function () {
      const id = this.dataset.circleId;
      const isPrivate = this.dataset.isPrivate === '1';
      const actionText = isPrivate ? 'mengajukan permintaan ke' : 'bergabung dengan';
      const circleName = this.closest('.list-group-item').querySelector('h5')?.textContent.trim();

      showConfirmationSnackbar(
        `Yakin ingin ${actionText} circle <strong>${circleName}</strong>?`,
        async () => {
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
              button.disabled = true;
              button.textContent = isPrivate ? 'Menunggu Persetujuan' : 'Tergabung';
            }
          } catch (err) {
            showSnackbar('Terjadi kesalahan saat mengirim permintaan.', 'danger');
          }
        }
      );
    });
  });
}

function showSnackbar(msg, type = 'info') {
  const container = document.getElementById('snackbar-container');
  container.innerHTML = `
    <div class="snackbar bg-${type} text-white shadow text-center">
      ${msg}
    </div>
  `;
  setTimeout(() => {
    container.innerHTML = '';
  }, 3500);
}

function showConfirmationSnackbar(message, onConfirm) {
  const container = document.getElementById('snackbar-container');
  container.innerHTML = `
    <div class="snackbar bg-dark border border-secondary text-white shadow text-center">
      <div class="mb-2">${message}</div>
      <div class="d-flex justify-content-center gap-2">
        <button class="btn btn-sm btn-outline-light px-3" id="confirm-yes">Ya, Lanjutkan</button>
        <button class="btn btn-sm btn-outline-secondary px-3" id="confirm-cancel">Batal</button>
      </div>
    </div>
  `;

  const yesBtn = document.getElementById('confirm-yes');
  const cancelBtn = document.getElementById('confirm-cancel');

  yesBtn.onclick = () => {
    container.innerHTML = '';
    onConfirm();
  };
  cancelBtn.onclick = () => {
    container.innerHTML = '';
  };
}

// Auto inisialisasi
if (typeof initJoinCircleButtons === 'function') {
  initJoinCircleButtons();
}
