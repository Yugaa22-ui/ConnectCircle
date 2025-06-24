<div class="modal fade" id="circleInfoModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content bg-dark text-light border border-secondary">

      <div class="modal-header border-bottom border-secondary" style="background-color: #1f1f1f;">
        <h5 class="modal-title text-light">Info Circle: <?= htmlspecialchars($circle_detail['name']) ?></h5>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <strong>Deskripsi:</strong>
          <p class="mt-2"><?= nl2br(htmlspecialchars($circle_detail['description'])) ?></p>
        </div>

        <div class="mb-3">
          <strong>Dibuat oleh:</strong>
          <div class="d-flex align-items-center mt-2">
            <img src="<?= $circle_detail['creator_photo'] ? '../assets/uploads/img/' . $circle_detail['creator_photo'] : '../assets/img/default.png' ?>"
                 class="rounded-circle me-2 border border-secondary" width="40" height="40" alt="Creator">
            <span><?= htmlspecialchars($circle_detail['creator_name']) ?></span>
          </div>
        </div>

        <hr class="border-secondary">

        <div class="mb-2">
          <strong>Anggota yang paling aktif (3):</strong>
        </div>

        <div id="top-member-list" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
          <div class="text-muted">Memuat anggota aktif...</div>
        </div>
      </div>

      <div class="modal-footer bg-dark border-top border-secondary">
        <button class="btn btn-outline-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
      </div>

    </div>
  </div>
</div>
<script>
document.getElementById('circleInfoModal').addEventListener('show.bs.modal', (event) => {
  const button = event.relatedTarget;
  const circleId = button.getAttribute('data-circle-id');

  const memberListContainer = document.getElementById('top-member-list');
  memberListContainer.innerHTML = `<div class="text-muted">Memuat anggota aktif...</div>`;

  fetch(`/connectcircle/backend/circle/get_top_members.php?circle_id=${circleId}`)
    .then(res => {
      if (!res.ok) throw new Error("HTTP error " + res.status);
      return res.json();
    })
    .then(members => {
      memberListContainer.innerHTML = '';
      members.forEach(member => {
        const imgSrc = member.profile_picture ? `/connectcircle/assets/uploads/img/${member.profile_picture}` : `/connectcircle/assets/img/default.png`;
        const muteIcon = member.is_muted == 1 ? `<i class="bi bi-volume-mute text-danger ms-1" title="Sedang dimute"></i>` : '';
        const card = `
          <div class="col">
            <div class="d-flex align-items-center bg-black rounded-3 p-2 border border-secondary">
              <img src="${imgSrc}" class="rounded-circle me-2 border border-secondary" width="40" height="40" alt="Member">
              <div class="flex-grow-1">
                <span class="text-light">${member.username}</span> ${muteIcon}<br>
                <small class="text-muted">${member.post_count} post</small>
              </div>
            </div>
          </div>
        `;
        memberListContainer.innerHTML += card;
      });

      if (members.length === 0) {
        memberListContainer.innerHTML = `<div class="text-muted">Belum ada aktivitas anggota.</div>`;
      }
    })
    .catch(err => {
      console.error('Gagal fetch anggota:', err);
      memberListContainer.innerHTML = `<div class="text-danger">Gagal memuat anggota.</div>`;
    });
});
</script>
