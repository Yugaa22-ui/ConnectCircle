document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("interestForm");
    if (form) {
      form.addEventListener("submit", (e) => {
        e.preventDefault();
        const formData = new FormData(form);
  
        fetch("../backend/admin/manage_interests_process.php", {
          method: "POST",
          body: formData
        })
          .then(res => res.text())
          .then(() => {
            // Reload konten setelah submit
            const activeLink = document.querySelector(".sidebar a[data-page='manage_interests.php']");
            if (activeLink) {
              activeLink.click();
            }
          })
          .catch(err => alert("Gagal menyimpan: " + err));
      });
    }
  
    const deleteButtons = document.querySelectorAll(".btn-delete");
    deleteButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        if (confirm("Yakin ingin menghapus minat ini?")) {
          const id = btn.dataset.id;
          fetch("../backend/admin/manage_interests_process.php?delete=" + id)
            .then(res => res.text())
            .then(() => {
              const activeLink = document.querySelector(".sidebar a[data-page='manage_interests.php']");
              if (activeLink) {
                activeLink.click();
              }
            })
            .catch(err => alert("Gagal menghapus: " + err));
        }
      });
    });
  });
  