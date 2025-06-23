<?php
$include_template = $_GET['embed'] ?? false;
if (!$include_template) include '../templates/header.php';
include '../backend/user/profile_data.php';
?>

<main class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card bg-dark border-secondary shadow">
        <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center">
          <h4 class="mb-0 text-white"><i class="bi bi-person-circle me-2"></i> Profil Saya</h4>
        </div>

        <div class="card-body">
          <div class="text-center mb-4">
            <img
              src="<?= $profile_picture ? '../assets/uploads/img/' . htmlspecialchars($profile_picture) : '../assets/img/default.png' ?>"
              class="rounded-circle shadow"
              width="120"
              height="120"
              alt="Foto Profil">
          </div>

          <ul class="list-group list-group-flush">
            <li class="list-group-item bg-dark text-white"><strong>Username:</strong> <?= htmlspecialchars($username) ?></li>
            <li class="list-group-item bg-dark text-white"><strong>Kota:</strong> <?= htmlspecialchars($city) ?></li>
            <li class="list-group-item bg-dark text-white"><strong>Profesi:</strong> <?= htmlspecialchars($profession) ?></li>
            <li class="list-group-item bg-dark text-white"><strong>Bio:</strong><br><?= nl2br(htmlspecialchars($bio)) ?></li>
            <li class="list-group-item bg-dark text-white">
              <strong>Minat:</strong><br>
              <?php if (!empty($interests_list)): ?>
                <?php foreach ($interests_list as $interest): ?>
                  <span class="badge interest-badge me-1 mb-1"><?= htmlspecialchars($interest) ?></span>
                <?php endforeach; ?>
              <?php else: ?>
                <span class="text-muted">Belum memilih minat</span>
              <?php endif; ?>
            </li>
          </ul>

          <h5 class="mt-4 text-white"><i class="bi bi-patch-check-fill me-1"></i> Badge yang Dimiliki</h5>
          <?php if ($badges_result->num_rows > 0): ?>
            <div class="list-group">
              <?php while ($badge = $badges_result->fetch_assoc()): ?>
                <div class="list-group-item bg-dark text-white border-secondary">
                  <i class="bi bi-award"></i>
                  <strong><?= htmlspecialchars($badge['name']) ?></strong><br>
                  <small><?= htmlspecialchars($badge['description']) ?></small>
                </div>
              <?php endwhile; ?>
            </div>
          <?php else: ?>
            <p class="text-muted">Belum ada badge.</p>
          <?php endif; ?>
        </div>

        <div class="card-footer border-top border-secondary d-flex justify-content-end gap-2">
          <a href="edit_profile.php" class="btn btn-outline-info"><i class="bi bi-pencil-square"></i> Edit Profil</a>
          <a href="change_password.php" class="btn btn-outline-warning"><i class="bi bi-key"></i> Ubah Password</a>
          <a href="dashboard_user.php" class="btn btn-outline-light"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
      </div>
    </div>
  </div>
</main>

<?php if (!$include_template) include '../templates/footer.php'; ?>
