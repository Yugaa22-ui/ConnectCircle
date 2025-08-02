<div class="modal fade" id="confirmLeaveModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border border-secondary">
      <div class="modal-header border-bottom border-secondary" style="background-color: #2c2c2c;">
        <h5 class="modal-title text-light">Konfirmasi Keluar Circle</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <p class="mb-0">Yakin ingin keluar dari circle ini?</p>
      </div>

      <div class="modal-footer border-top border-secondary">
        <form method="POST" class="d-inline">
          <input type="hidden" name="leave_confirm" value="yes">
          <input type="hidden" name="circle_id" value="<?= $circle_id ?>">
          <button type="submit" class="btn btn-outline-danger rounded-pill px-4">Ya, Keluar</button>
        </form>
        <button type="button" class="btn btn-outline-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>
