<?php include '../backend/admin/manage_users_process.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengguna - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Daftar Pengguna</h4>
        </div>
        <div class="card-body">
            <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Kota</th>
                                <th>Profesi</th>
                                <th>Role</th>
                                <th>Terdaftar</th>
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
                                    <td><?= htmlspecialchars($row['role']) ?></td>
                                    <td><?= $row['created_at'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Belum ada pengguna terdaftar.</div>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="dashboard_admin.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
