<?php
include '../backend/auth/auth_check.php';
include '../backend/user/edit_profile_process.php';
include '../templates/header.php';
?>


<main class="container py-5">

    <link href="../css/cropper-style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header card-header-dark">
          <h3 class="mb-0">Edit Profil</h3>
        </div>
        <div class="card-body">

          <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
          <?php elseif (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <form method="POST" action="" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="mb-3">
              <label class="form-label text-white">Username *</label>
              <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label text-white">Email *</label>
              <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label text-white">Kota</label>
              <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($city) ?>">
            </div>

            <div class="mb-3">
              <label class="form-label text-white">Profesi</label>
              <input type="text" name="profession" class="form-control" value="<?= htmlspecialchars($profession) ?>">
            </div>

            <div class="mb-3">
              <label class="form-label text-white">Bio</label>
              <textarea name="bio" class="form-control" rows="3"><?= htmlspecialchars($bio) ?></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label text-white">Minat (maksimal 3)</label>
              <div class="d-flex flex-wrap gap-2" id="interests-container">
                <?php foreach ($all_interests as $index => $int): ?>
                  <?php
                    $selected = in_array($int['id'], array_column($user_interests, 'interest_id'));
                    $checkboxId = 'interest_' . $index;
                  ?>
                  <input type="checkbox" class="btn-check interest-checkbox" name="interests[]" id="<?= $checkboxId ?>" value="<?= $int['id'] ?>" <?= $selected ? 'checked' : '' ?>>
                  <label class="btn btn-outline-info text-white" for="<?= $checkboxId ?>"><?= htmlspecialchars($int['name']) ?></label>
                <?php endforeach; ?>
              </div>
              <div id="interest-error" class="form-error mt-2 d-none text-danger">Maksimal hanya bisa memilih 3 minat.</div>
            </div>

            <div class="mb-3">
              <label class="form-label text-white">Foto Profil</label><br>
              <img id="preview" src="<?= $profile_picture ? '../assets/uploads/img/' . htmlspecialchars($profile_picture) : '#' ?>" class="<?= $profile_picture ? '' : 'd-none' ?> rounded-circle mb-2" width="100">
              <input type="file" name="profile_picture" id="profileInput" class="form-control" accept="image/*">
              <input type="hidden" name="cropped_image" id="cropped_image_input">
            </div>

            <div class="d-flex justify-content-between flex-wrap gap-2">
              <button type="submit" class="btn btn-outline-light">Simpan Perubahan</button>
              <a href="profile.php" class="btn btn-secondary">Kembali</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Modal Cropper -->
<div class="modal fade" id="cropperModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">Crop Foto Profil</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img id="cropperImage" class="img-fluid" src="">
      </div>
      <div class="modal-footer border-secondary">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-outline-light" id="cropBtn">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="../js/components/cropper-handler.js"></script>
<script src="../js/user/limit_interest.js"></script>
<?php include '../templates/footer.php'; ?>
