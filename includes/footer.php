  <?php if (isLoggedIn()): ?>
  </div><!-- /.main-content -->
  <?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= baseUrl() ?>assets/js/script.js"></script>
<?php if (!empty($flashSuccess)): ?>
<script>Swal.fire({icon:'success', title:'<?= addslashes($flashSuccess) ?>', timer:2200, showConfirmButton:false, toast:true, position:'top-end'});</script>
<?php endif; ?>
<?php if (!empty($flashError)): ?>
<script>Swal.fire({icon:'error', title:'<?= addslashes($flashError) ?>'});</script>
<?php endif; ?>
</body>
</html>
