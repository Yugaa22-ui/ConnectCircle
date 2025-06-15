<div class="modal fade" id="circleInfoModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Info Circle: <?= htmlspecialchars($circle_detail['name']) ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($circle_detail['description'])) ?></p>
        <p><strong>Dibuat oleh:</strong></p>
        <div class="d-flex align-items-center mb-3">
          <img src="<?= $circle_detail['creator_photo'] ? '../assets/uploads/img/' . $circle_detail['creator_photo'] : '../assets/img/default.png' ?>" class="rounded-circle me-2" width="40" height="40">
          <?= htmlspecialchars($circle_detail['creator_name']) ?>
        </div>
        <hr>
        <p><strong>Anggota:</strong></p>
        <ul class="list-unstyled row">
          <?php foreach ($circle_detail['members'] as $member): ?>
            <li class="col-md-4 d-flex align-items-center mb-2">
              <img src="<?= isset($member['profile_picture']) && $member['profile_picture'] ? '../assets/uploads/img/' . $member['profile_picture'] : '../assets/img/default.png' ?>" class="rounded-circle me-2" width="40" height="40">
              <?= htmlspecialchars($member['username']) ?>
              <?php if (!empty($member['is_muted'])): ?>
                <span class="text-danger ms-1" title="Sedang dimute"><i class="bi bi-volume-mute"></i></span>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>