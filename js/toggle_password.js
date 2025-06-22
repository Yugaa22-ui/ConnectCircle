document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function () {
      const input = document.getElementById(this.dataset.target);
      const icon = document.getElementById('icon-' + this.dataset.target);
  
      if (!input || !icon) return; // validasi keamanan
  
      const isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';
      icon.classList.toggle('bi-eye', isPassword);
      icon.classList.toggle('bi-eye-slash', !isPassword);
    });
  });
  