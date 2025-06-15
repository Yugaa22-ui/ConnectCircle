<div class="modal fade" id="confirmLeaveModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title">Konfirmasi Keluar Circle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">Yakin ingin keluar dari circle ini?</div>
      <div class="modal-footer">
        <form method="POST">
          <input type="hidden" name="leave_confirm" value="yes">
          <input type="hidden" name="circle_id" value="<?= $circle_id ?>">
          <button type="submit" class="btn btn-danger">Ya, Keluar</button>
        </form>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>