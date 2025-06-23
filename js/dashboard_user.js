// Fungsi bantu: muat skrip JS jika belum ada
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

// Jalankan saat halaman siap
document.querySelectorAll('[data-page]').forEach(link => {
  link.addEventListener('click', function (e) {
    e.preventDefault();

    // Ambil href dan tambahkan ?embed=1 jika perlu
    let page = this.getAttribute('href');
    if (!page.includes('?')) {
      page += '?embed=1';
    } else if (!page.includes('embed=1')) {
      page += '&embed=1';
    }

    fetch(page)
      .then(res => res.text())
      .then(html => {
        document.getElementById('content-area').innerHTML = html;

        // Jika memuat halaman tertentu, muat JS terkait
        if (page.includes('create_circle.php')) {
          const script = document.createElement('script');
          script.src = '../js/create_circle.js';
          script.onload = () => {
          if (typeof initCreateCircleForm === 'function') {
              initCreateCircleForm();
          }
          };
          document.body.appendChild(script);
      }

      if (page.includes('join_circle.php')) {
        loadScriptIfNotExists('../js/join_circle.js', () => {
          if (typeof initJoinCircleButtons === 'function') {
            initJoinCircleButtons();
          }
        });
      }      
        // Tutup sidebar mobile jika terbuka
        const sidebar = bootstrap.Offcanvas.getInstance(document.getElementById('mobileSidebar'));
        if (sidebar) sidebar.hide();
      });
  });
});
