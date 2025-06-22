document.querySelectorAll('#sidebar a[data-page]').forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      const page = this.dataset.page;
      fetch(page)
        .then(res => res.text())
        .then(html => {
          document.getElementById('content-area').innerHTML = html;
        });
    });
  });
  
  document.getElementById('toggleSidebar').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('d-none');
  });
  