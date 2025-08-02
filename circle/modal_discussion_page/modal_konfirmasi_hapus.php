<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white border border-secondary">
      <form method="POST" id="deletePostForm">
        <div class="modal-header border-0 border-bottom border-secondary">
          <h5 class="modal-title text-danger">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Konfirmasi Hapus Pesan
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Apakah Anda yakin ingin menghapus pesan ini?
          <input type="hidden" name="delete_post_id" id="deletePostId">
        </div>
        <div class="modal-footer border-0 border-top border-secondary">
          <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Batal
          </button>
          <button type="submit" class="btn btn-danger">
            <i class="bi bi-trash me-1"></i> Hapus
          </button>
        </div>
      </form>
    </div>
  </div>
</div>