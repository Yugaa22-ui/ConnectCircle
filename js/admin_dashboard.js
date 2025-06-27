document.addEventListener("DOMContentLoaded", () => {
    const navLinks = document.querySelectorAll("a[data-page]");
    const contentContainer = document.getElementById("admin-content");
  
    navLinks.forEach(link => {
      link.addEventListener("click", (e) => {
        e.preventDefault();
  
        // Aktifkan link
        navLinks.forEach(l => l.classList.remove("active"));
        link.classList.add("active");
  
        // Tampilkan spinner loading
        contentContainer.innerHTML = `
          <div class="d-flex justify-content-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        `;
  
        // Load konten
        fetch(link.getAttribute("data-page"))
          .then(response => {
            if (!response.ok) throw new Error("Gagal memuat konten.");
            return response.text();
          })
          .then(html => {
            contentContainer.innerHTML = html;
          })
          .catch(err => {
            contentContainer.innerHTML = `
              <div class="alert alert-danger mt-3">Terjadi kesalahan: ${err.message}</div>
            `;
          });
      });
    });
  });
  