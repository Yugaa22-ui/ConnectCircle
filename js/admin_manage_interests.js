function initManageInterests(container) {
  console.log("✅ initManageInterests dijalankan");

  const form = container.querySelector("#interestForm");
  console.log("📌 Form interestForm:", form);

  const deleteButtons = container.querySelectorAll(".btn-delete");
  console.log(`📌 Jumlah tombol hapus ditemukan: ${deleteButtons.length}`);

  if (form) {
    console.log("✅ Menambahkan event listener submit pada form");
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      console.log("✅ Form submit ditekan");
      const formData = new FormData(form);

      fetch("../backend/admin/manage_interests_process.php", {
        method: "POST",
        body: formData
      })
        .then(res => {
          console.log("✅ Response status POST:", res.status);
          return res.text();
        })
        .then(text => {
          console.log("✅ Response text POST:", text);
          const activeLink = document.querySelector(".sidebar a[data-page='manage_interests.php']");
          if (activeLink) activeLink.click();
        })
        .catch(err => alert("Gagal menyimpan: " + err));
    });
  }

  deleteButtons.forEach(btn => {
    console.log("✅ Menambahkan event listener klik pada tombol hapus");
    btn.addEventListener("click", () => {
      console.log("✅ Tombol hapus ditekan");
      if (confirm("Yakin ingin menghapus minat ini?")) {
        const id = btn.dataset.id;
        fetch("../backend/admin/manage_interests_process.php?delete=" + id)
          .then(res => {
            console.log("✅ Response status DELETE:", res.status);
            return res.text();
          })
          .then(text => {
            console.log("✅ Response text DELETE:", text);
            const activeLink = document.querySelector(".sidebar a[data-page='manage_interests.php']");
            if (activeLink) activeLink.click();
          })
          .catch(err => alert("Gagal menghapus: " + err));
      }
    });
  });
}
