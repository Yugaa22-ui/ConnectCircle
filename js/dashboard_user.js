document.querySelectorAll('[data-page]').forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
  
      // Ambil href dan tambahkan ?embed=1 (jika belum ada query)
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
  
          // Tutup sidebar (offcanvas) jika sedang di mobile
          const sidebar = bootstrap.Offcanvas.getInstance(document.getElementById('mobileSidebar'));
          if (sidebar) sidebar.hide();
        });
    });
  });
  