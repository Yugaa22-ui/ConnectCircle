// Fungsi bantu: memuat script jika belum ada
function loadScriptIfNotExists(src, callback) {
    if (document.querySelector(`script[src="${src}"]`)) {
      if (callback) callback();
      return;
    }
    const script = document.createElement('script');
    script.src = src;
    script.onload = () => callback && callback();
    document.body.appendChild(script);
  }
  
  // Saat dokumen siap
  document.querySelectorAll('[data-page]').forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
  
      const page = this.getAttribute('data-page');
  
      // Spinner loading
      const container = document.getElementById('admin-content');
      container.innerHTML = `
        <div class="d-flex justify-content-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      `;
  
      fetch(page)
        .then(res => {
          if (!res.ok) throw new Error('Gagal memuat konten.');
          return res.text();
        })
        .then(html => {
          container.innerHTML = html;
  
          // Tandai menu aktif
          setActiveLink(this);
  
          // Load script khusus halaman
          if (page.includes('manage_interests.php')) {
            loadScriptIfNotExists('../js/admin_manage_interests.js', () => {
              if (typeof initManageInterests === 'function') {
                initManageInterests();
              }
            });
          }
  
          if (page.includes('manage_users.php')) {
            loadScriptIfNotExists('../js/admin_manage_users.js', () => {
              if (typeof initManageUsers === 'function') {
                initManageUsers();
              }
            });
          }
  
          if (page.includes('manage_roles.php')) {
            loadScriptIfNotExists('../js/admin_manage_roles.js', () => {
              if (typeof initManageRoles === 'function') {
                initManageRoles();
              }
            });
          }
  
          // Tutup sidebar mobile jika terbuka
          const sidebar = bootstrap.Offcanvas.getInstance(document.getElementById('mobileSidebar'));
          if (sidebar) sidebar.hide();
        })
        .catch(err => {
          container.innerHTML = `
            <div class="alert alert-danger mt-3">
              Terjadi kesalahan: ${err.message}
            </div>
          `;
        });
    });
  });
  
  // Fungsi aktif menu
  function setActiveLink(clickedLink) {
    const allLinks = document.querySelectorAll('.sidebar-link');
    allLinks.forEach(link => link.classList.remove('active'));
    clickedLink.classList.add('active');
  }
  