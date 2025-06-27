<?php
include '../backend/admin/dashboard_stats.php';
?>
<div class="row g-3">
  <div class="col-md-6 col-lg-3">
    <div class="card bg-primary text-white shadow-sm">
      <div class="card-body">
        <h5><i class="bi bi-people-fill me-2"></i>Total Pengguna</h5>
        <h2><?= $totalUsers ?></h2>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card bg-success text-white shadow-sm">
      <div class="card-body">
        <h5><i class="bi bi-list-check me-2"></i>Total Minat</h5>
        <h2><?= $totalInterests ?></h2>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card bg-warning text-white shadow-sm">
      <div class="card-body">
        <h5><i class="bi bi-chat-dots me-2"></i>Total Circle</h5>
        <h2><?= $totalCircles ?></h2>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card bg-danger text-white shadow-sm">
      <div class="card-body">
        <h5><i class="bi bi-award me-2"></i>Total Badge</h5>
        <h2><?= $totalBadges ?></h2>
      </div>
    </div>
  </div>
</div>
