function initViewCircleSearch() {
    const form = document.querySelector('.search-form');
    if (!form) return;
  
    form.addEventListener('submit', function (e) {
      e.preventDefault();
  
      const query = form.querySelector('input[name="search"]').value;
      const resultsContainer = document.getElementById('circle-results');
  
      fetch(`../backend/circle/view_circle_ajax.php?search=${encodeURIComponent(query)}`)
        .then(res => res.text())
        .then(html => {
          resultsContainer.innerHTML = html;
        })
        .catch(() => {
          resultsContainer.innerHTML = `<div class="alert alert-danger">Gagal memuat data.</div>`;
        });
    });
  }
  
  // Auto-inisialisasi jika dipanggil langsung
  if (typeof initViewCircleSearch === 'function') {
    initViewCircleSearch();
  }
  