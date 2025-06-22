// Batasi maksimal 3 minat
document.querySelectorAll('.interest-checkbox').forEach(cb => {
    cb.addEventListener('change', function () {
      const checked = document.querySelectorAll('.interest-checkbox:checked');
      const errorMsg = document.getElementById('interest-error');
      if (checked.length > 3) {
        this.checked = false;
        errorMsg.classList.remove('d-none');
      } else {
        errorMsg.classList.add('d-none');
      }
    });
  });
  