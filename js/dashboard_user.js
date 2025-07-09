function loadScriptIfNotExists(src, callback) {
  console.log("🔍 Memeriksa apakah script sudah dimuat:", src);

  // Cari elemen script lama dan hapus
  const oldScript = document.querySelector(`script[src="${src}"]`);
  if (oldScript) {
    console.log("♻️ Script sudah ada, akan dihapus dan dimuat ulang:", src);
    oldScript.remove();
  }

  // Tambah script baru
  const script = document.createElement('script');
  script.src = src + '?v=' + Date.now(); // Tambahkan timestamp biar tidak cache
  script.onload = () => {
    console.log("✅ Script berhasil dimuat:", src);
    if (callback) callback();
  };
  script.onerror = () => {
    console.error("❌ Gagal memuat script:", src);
  };
  document.body.appendChild(script);
}

// Jalankan saat halaman siap
document.querySelectorAll('[data-page]').forEach(link => {
  link.addEventListener('click', function (e) {
    e.preventDefault();

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

        // Set active menu
        setActiveLink(this);

        // Tambahkan loader script sesuai halaman
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

        if (page.includes('view_circle.php')) {
          loadScriptIfNotExists('../js/view_circle.js', () => {
            if (typeof initViewCircleSearch === 'function') initViewCircleSearch();
            if (typeof initCancelJoinRequest === 'function') initCancelJoinRequest();
          });
        }

        if (page.includes('search.php')) {
          loadScriptIfNotExists('../js/search_friend.js', () => {
            let tries = 0;
            const interval = setInterval(() => {
              if (typeof window.initSearchFriendForm === 'function') {
                window.initSearchFriendForm();
                clearInterval(interval);
              } else if (tries++ > 10) {
                clearInterval(interval);
              }
            }, 100);
          });
        }

        if (page.includes('friend_request.php')) {
          loadScriptIfNotExists('../js/friend_request.js', () => {
            setTimeout(() => {
              if (typeof window.initFriendRequestHandler === 'function') {
                window.initFriendRequestHandler();
              }
            }, 100);
          });
        }

        if (page.includes('friend_list.php')) {
          loadScriptIfNotExists('../js/friend_list.js', () => {
            console.log("✅ friend_list.js sudah dimuat, memulai polling...");
        
            // Polling hingga fungsi tersedia
            let tries = 0;
            const interval = setInterval(() => {
              if (typeof window.initFriendListHandler === 'function') {
                console.log("✅ initFriendListHandler ditemukan dan dipanggil");
                window.initFriendListHandler();
                clearInterval(interval);
              } else if (tries++ > 10) {
                console.warn("❌ initFriendListHandler tidak ditemukan setelah 1 detik.");
                clearInterval(interval);
              }
            }, 100);
          });
        }                        

        // Tutup sidebar mobile jika terbuka
        const sidebar = bootstrap.Offcanvas.getInstance(document.getElementById('mobileSidebar'));
        if (sidebar) sidebar.hide();
      });
  });
});

function setActiveLink(clickedLink) {
  const allLinks = document.querySelectorAll('.sidebar-link');
  const clickedHref = clickedLink.getAttribute('href').split('?')[0];

  allLinks.forEach(link => {
    const href = link.getAttribute('href').split('?')[0];
    if (href === clickedHref) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });
}
