<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editPostForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Pesan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <textarea name="new_content" id="editContent" class="form-control" rows="4"></textarea>
          <input type="hidden" name="post_id" id="editPostId">
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <div id="editSnackbar" class="text-danger small me-auto"></div>
          <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>