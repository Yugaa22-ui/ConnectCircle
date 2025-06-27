function showNotification(message, type = "success") {
    const container = document.getElementById("notification-container");
    if (!container) return;
  
    const notif = document.createElement("div");
    notif.className = `snackbar bg-${type === "success" ? "success" : "danger"} text-white`;
    notif.textContent = message;
  
    container.appendChild(notif);
  
    setTimeout(() => {
      notif.classList.add("show");
    }, 10);
  
    setTimeout(() => {
      notif.classList.remove("show");
      setTimeout(() => container.removeChild(notif), 300);
    }, 3000);
  }
  
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
            showNotification(data.success, "success");
            const activeLink = document.querySelector(".sidebar a[data-page='manage_roles.php']");
            if (activeLink) activeLink.click();
          } else if (data.error) {
            showNotification(data.error, "error");
          }
        })
        .catch(err => {
          console.error("❌ Error:", err);
          showNotification("Gagal menyimpan: " + err, "error");
        });
      });
    });
  }
  