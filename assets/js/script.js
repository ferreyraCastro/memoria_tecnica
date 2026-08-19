// Toggle de sidebar en mobile
document.addEventListener('DOMContentLoaded', function () {
  const btn = document.getElementById('btnToggleSidebar');
  const sidebar = document.getElementById('sidebar');
  const backdrop = document.getElementById('sidebarBackdrop');

  function closeSidebar() {
    sidebar && sidebar.classList.remove('show');
    document.body.classList.remove('sidebar-open');
  }

  if (btn && sidebar) {
    btn.addEventListener('click', function () {
      sidebar.classList.toggle('show');
      document.body.classList.toggle('sidebar-open');
    });
  }
  if (backdrop) backdrop.addEventListener('click', closeSidebar);
});

// Confirmación genérica de borrado con SweetAlert2
function confirmarBorrado(url, texto) {
  Swal.fire({
    title: '¿Estás seguro?',
    text: texto || 'Esta acción no se puede deshacer.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = url;
    }
  });
  return false;
}

// Mostrar / ocultar contraseña en campos con clase .toggle-password
function togglePassword(btn, targetId) {
  const field = document.getElementById(targetId);
  if (!field) return;
  if (field.type === 'password') {
    field.type = 'text';
    btn.innerHTML = '<i class="bi bi-eye-slash"></i>';
  } else {
    field.type = 'password';
    btn.innerHTML = '<i class="bi bi-eye"></i>';
  }
}

// Copiar al portapapeles con feedback de SweetAlert (toast)
function copiarTexto(texto) {
  navigator.clipboard.writeText(texto).then(function () {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: 'Copiado al portapapeles',
      showConfirmButton: false,
      timer: 1500
    });
  });
}
