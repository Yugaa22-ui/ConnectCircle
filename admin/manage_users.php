<?php
include '../backend/admin/manage_users_process.php';
?>

<div class="container-fluid py-3">
  <div class="card bg-dark text-white shadow-sm">
    <div class="card-header border-secondary d-flex justify-content-between align-items-center">
      <h4 class="mb-0">
        <i class="bi bi-people-fill me-2"></i>Daftar Pengguna
      </h4>->
    </div>
    <div class="card-body p-0">
      <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
          <table class="table table-dark table-striped table-hover mb-0 align-middle">
            <thead class="table-secondary text-dark">
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Kota</th>
                <th scope="col">Profesi</th>
                <th scope="col">Role</th>
                <th scope="col">Terdaftar</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= $row['id'] ?></td>
                  <td><?= htmlspecialchars($row['username']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td><?= htmlspecialchars($row['city']) ?></td>
                  <td><?= htmlspecialchars($row['profession']) ?></td>
                  <td>
                    <span class="badge bg-<?php
                      switch($row['role']) {
                        case 'admin': echo 'danger'; break;
                        case 'moderator': echo 'warning text-dark'; break;
                        default: echo 'secondary'; break;
                      }
                    ?>">
                      <?= htmlspecialchars($row['role']) ?>
                    </span>
                  </td>
                  <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-info mb-0">Belum ada pengguna terdaftar.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script src="js/admin_manage_users.js"></script>