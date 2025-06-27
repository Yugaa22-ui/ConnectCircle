function initManageRoles() {
    console.log("✅ initManageRoles dijalankan");
  
    const forms = document.querySelectorAll(".form-role");
    forms.forEach(form => {
      form.addEventListener("submit", (e) => {
        e.preventDefault();
  
        const formData = new FormData(form);
        console.log("📌 Data akan dikirim:");
        for (let pair of formData.entries()) {
          console.log(pair[0]+ ': ' + pair[1]);
        }
  
        fetch("../backend/admin/manage_roles_process.php", {
          method: "POST",
          headers: {
            "X-Requested-With": "XMLHttpRequest"
          },
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert(data.success);
            const activeLink = document.querySelector(".sidebar a[data-page='manage_roles.php']");
            if (activeLink) activeLink.click();
          } else if (data.error) {
            alert("Gagal: " + data.error);
          }
        })
        .catch(err => {
          console.error("❌ Error:", err);
          alert("Gagal menyimpan: " + err);
        });
      });
    });
  }
  