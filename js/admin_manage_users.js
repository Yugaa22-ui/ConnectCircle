document.addEventListener("DOMContentLoaded", () => {
    const refreshButton = document.getElementById("refreshUsers");
    if (refreshButton) {
      refreshButton.addEventListener("click", () => {
        refreshButton.disabled = true;
        refreshButton.innerHTML = `<i class="bi bi-arrow-clockwise"></i> Memuat...`;
  
        fetch("manage_users.php")
          .then(res => res.text())
          .then(html => {
            const tempDiv = document.createElement("div");
            tempDiv.innerHTML = html;
  
            const newTable = tempDiv.querySelector(".container-fluid");
            const container = document.querySelector(".container-fluid");
  
            if (newTable && container) {
              container.style.opacity = "0.5";
              setTimeout(() => {
                container.innerHTML = newTable.innerHTML;
                container.style.opacity = "1";
              }, 200);
            }
          })
          .catch(err => alert("Gagal memuat data: " + err))
          .finally(() => {
            refreshButton.disabled = false;
            refreshButton.innerHTML = `<i class="bi bi-arrow-clockwise"></i> Muat Ulang`;
          });
      });
    }
  });
  