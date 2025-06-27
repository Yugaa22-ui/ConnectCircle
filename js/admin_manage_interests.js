function initManageInterests(container) {
  console.log("✅ initManageInterests dijalankan");

  const form = container.querySelector("#interestForm");
  const listGroup = container.querySelector(".list-group") || createListGroup(container);

  function createListGroup(container) {
    const ul = document.createElement("ul");
    ul.className = "list-group list-group-flush";
    container.appendChild(ul);
    return ul;
  }

  // Toast function
  function showToast(message, type = "success") {
    const toastContainer = document.getElementById("toastContainer");
    const toast = document.createElement("div");
    toast.className = `toast align-items-center text-bg-${type} border-0 show`;
    toast.setAttribute("role", "alert");
    toast.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>`;
    toastContainer.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
  }

  // Handle form submit
  if (form) {
    form.addEventListener("submit", e => {
      e.preventDefault();
      const formData = new FormData(form);

      fetch("../backend/admin/manage_interests_process.php", {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showToast(data.success, "success");
          form.reset();
          addInterestItem(data.id, data.name);
        } else if (data.error) {
          showToast(data.error, "danger");
        }
      })
      .catch(err => showToast("Gagal menyimpan: " + err, "danger"));
    });
  }

  // Bind existing delete buttons
  container.querySelectorAll(".btn-delete").forEach(btn => {
    const li = btn.closest("li");
    btn.addEventListener("click", () => confirmDelete(btn.dataset.id, li));
  });

  // Tambah elemen list baru
  function addInterestItem(id, name) {
    const li = document.createElement("li");
    li.className = "list-group-item bg-dark text-white d-flex justify-content-between align-items-center";
    li.style.opacity = "0";
    li.innerHTML = `
      ${name}
      <button class="btn btn-sm btn-danger btn-delete" data-id="${id}">
        <i class="bi bi-trash"></i> Hapus
      </button>`;
    listGroup.appendChild(li);

    setTimeout(() => li.style.transition = "opacity 0.3s", 10);
    setTimeout(() => li.style.opacity = "1", 20);

    const btn = li.querySelector(".btn-delete");
    btn.addEventListener("click", () => confirmDelete(id, li));
  }

  // Modal konfirmasi hapus
  function confirmDelete(id, li) {
    const modalHtml = `
      <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content bg-dark text-white">
            <div class="modal-header">
              <h5 class="modal-title">Konfirmasi Hapus</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              Yakin ingin menghapus minat ini?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
            </div>
          </div>
        </div>
      </div>`;
    document.body.insertAdjacentHTML("beforeend", modalHtml);
    const modal = new bootstrap.Modal(document.getElementById("confirmModal"));
    modal.show();

    document.getElementById("confirmDeleteBtn").addEventListener("click", () => {
      fetch(`../backend/admin/manage_interests_process.php?delete=${id}`)
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            li.style.transition = "opacity 0.3s";
            li.style.opacity = "0";
            setTimeout(() => li.remove(), 300);
            showToast(data.success, "success");
          } else if (data.error) {
            showToast(data.error, "danger");
          }
        })
        .catch(err => showToast("Gagal menghapus: " + err, "danger"))
        .finally(() => modal.hide());
    });

    document.getElementById("confirmModal").addEventListener("hidden.bs.modal", () => {
      document.getElementById("confirmModal").remove();
    });
  }
}
