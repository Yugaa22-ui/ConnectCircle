document.querySelectorAll('[data-page]').forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      const page = this.getAttribute('href');
      fetch(page)
        .then(res => res.text())
        .then(html => {
          document.getElementById('content-area').innerHTML = html;
          // close sidebar offcanvas (if mobile)
          const sidebar = bootstrap.Offcanvas.getInstance(document.getElementById('mobileSidebar'));
          if (sidebar) sidebar.hide();
        });
    });
  });
  