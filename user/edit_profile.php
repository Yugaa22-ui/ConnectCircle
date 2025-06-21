<?php
include '../backend/auth/auth_check.php';
include '../backend/user/edit_profile_process.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap & Cropper CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/cropper-style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <style>
        .form-error { color: red; font-size: 0.9em; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3>Edit Profil</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php elseif (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kota</label>
                    <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($city) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Profesi</label>
                    <input type="text" name="profession" class="form-control" value="<?= htmlspecialchars($profession) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" class="form-control" rows="3"><?= htmlspecialchars($bio) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Minat (maksimal 3)</label>
                    <div class="d-flex flex-wrap gap-2" id="interests-container">
                        <?php foreach ($all_interests as $index => $int): ?>
                            <?php
                                $selected = in_array($int['id'], array_column($user_interests, 'interest_id'));
                                $checkboxId = 'interest_' . $index;
                            ?>
                            <input type="checkbox" class="btn-check interest-checkbox" name="interests[]" id="<?= $checkboxId ?>" value="<?= $int['id'] ?>" autocomplete="off" <?= $selected ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="<?= $checkboxId ?>"><?= htmlspecialchars($int['name']) ?></label>
                        <?php endforeach; ?>
                    </div>
                    <div id="interest-error" class="form-error mt-1 d-none">Maksimal hanya bisa memilih 3 minat.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto Profil</label><br>
                    <img id="preview" src="<?= $profile_picture ? '../assets/uploads/img/' . htmlspecialchars($profile_picture) : '#' ?>" class="<?= $profile_picture ? '' : 'd-none' ?>" width="100">
                    <input type="file" name="profile_picture" id="profileInput" class="form-control mt-2" accept="image/*">
                </div>

                <input type="hidden" name="cropped_image" id="cropped_image_input">

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="profile.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cropper -->
<div class="modal fade" id="cropperModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center">
                <img id="cropperImage" class="img-fluid" src="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="cropBtn">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="../js/cropper-handler.js"></script>
<script>
    // Batas maksimal 3 checkbox minat
    document.querySelectorAll('.interest-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            const checked = document.querySelectorAll('.interest-checkbox:checked');
            const errorMsg = document.getElementById('interest-error');
            if (checked.length > 3) {
                this.checked = false;
                errorMsg.classList.remove('d-none');
            } else {
                errorMsg.classList.add('d-none');
            }
        });
    });
</script>
</body>
</html>
