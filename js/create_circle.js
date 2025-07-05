function initCreateCircleForm() {
  const form = document.getElementById('createCircleForm');
  const alertDiv = document.getElementById('formAlert');
  const interestError = document.getElementById('interest-error');

  if (!form) return;

  form.addEventListener('submit', async function(e) {
    e.preventDefault();

    // Reset
    alertDiv.innerHTML = '';
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    interestError.textContent = '';

    // Cek minat terpilih
    const selectedInterest = form.querySelector('input[name="interest_id"]:checked');
    if (!selectedInterest) {
      interestError.textContent = "Wajib memilih 1 minat circle.";
      return;
    }

    const formData = new FormData(form);
    formData.set('interest_id', selectedInterest.value);

    try {
      const res = await fetch('/connectcircle/backend/circle/create_circle_process.php', {
        method: 'POST',
        body: formData
      });      

      const data = await res.json();

      if (data.success) {
        alertDiv.innerHTML = `<div class="alert alert-success">${data.success}</div>`;
        form.reset();
      } else if (data.errors) {
        for (const [field, msg] of Object.entries(data.errors)) {
          if (field === "interest_id") {
            interestError.textContent = msg;
          } else {
            const input = document.getElementById(field);
            const errorDiv = document.getElementById('error_' + field);
            if (input) input.classList.add('is-invalid');
            if (errorDiv) errorDiv.textContent = msg;
          }
        }
      }
    } catch (err) {
      alertDiv.innerHTML = `<div class="alert alert-danger">Terjadi kesalahan koneksi.</div>`;
    }
  });
}
