document.addEventListener("DOMContentLoaded", () => {
    const forms = document.querySelectorAll(".role-form");
  
    forms.forEach(form => {
      form.addEventListener("submit", (e) => {
        e.preventDefault();
  
        const formData = new FormData(form);
  
        fetch("../backend/admin/manage_roles_process.php", {
          method: "POST",
          body: formData
        })
        .then(res => res.text())
        .then(() => {
          // Reload konten setelah ubah role
          const activeLink = document.querySelector(".sidebar a[data-page='manage_roles.php']");
          if (activeLink) activeLink.click();
        })
        .catch(err => {
          alert("Gagal menyimpan: " + err);
        });
      });
    });
  });
  