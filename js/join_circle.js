function initJoinCircleButtons() {
  const modal = new bootstrap.Modal(document.getElementById('confirmJoinModal'));
  const confirmMessage = document.getElementById('confirmJoinMessage');
  const confirmBtn = document.getElementById('confirmJoinBtn');

  document.querySelectorAll('.join-btn:not(.processed)').forEach(button => {
    button.classList.add('processed');

    button.addEventListener('click', function () {
      const circleId = this.dataset.circleId;
      const isPrivate = this.dataset.isPrivate === '1';
      const circleName = this.closest('.list-group-item').querySelector('h5')?.textContent.trim();
      const actionText = isPrivate ? 'mengajukan permintaan ke' : 'bergabung dengan';

      confirmMessage.innerHTML = `Yakin ingin ${actionText} circle <strong>${circleName}</strong>?`;
      confirmBtn.dataset.circleId = circleId;
      confirmBtn.dataset.isPrivate = isPrivate;
      confirmBtn.dataset.buttonRef = button.dataset.circleId;

      modal.show();
    });
  });

  confirmBtn.addEventListener('click', async function () {
    const id = this.dataset.circleId;
    const isPrivate = this.dataset.isPrivate === 'true';
  
    // Feedback loading
    confirmBtn.disabled = true;
    confirmBtn.textContent = 'Memproses...';
  
    try {
      const res = await fetch(`../backend/circle/join_circle_process.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ circle_id: id, is_private: isPrivate ? 1 : 0 })
      });
  
      const result = await res.json();
      showSnackbar(result.success || result.error, result.success ? 'success' : 'danger');
  
      if (result.success) {
        document.querySelectorAll(`.join-btn[data-circle-id="${id}"]`).forEach(btn => {
          btn.disabled = true;
          btn.textContent = isPrivate ? 'Menunggu Persetujuan' : 'Tergabung';
        });
      }
  
    } catch {
      showSnackbar('Terjadi kesalahan saat mengirim permintaan.', 'danger');
    } finally {
      // Reset tombol
      confirmBtn.disabled = false;
      confirmBtn.textContent = 'Ya, Lanjutkan';
      bootstrap.Modal.getInstance(document.getElementById('confirmJoinModal')).hide();
    }
  });  
}
