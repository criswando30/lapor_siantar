<?= $this->extend('admin/layouts/admin'); ?>
<?= $this->section('content'); ?>


<?php $row = $row ?? []; ?>

<div class="page">
  <div class="page__header">
    <div>
      <h1 class="page__title"><?= esc($title); ?></h1>
      <p class="page__subtitle">Isi data user dengan benar.</p>
    </div>
    <a class="btn btn--ghost" href="<?= site_url('admin/users'); ?>">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>
  </div>

  <section class="panel">
    <div class="panel__body">

      <form method="post" action="<?= ($mode === 'edit')
          ? site_url('admin/users/' . $row['id'] . '/update')
          : site_url('admin/users/store'); ?>">
        <?= csrf_field(); ?>

        <div style="display:grid; gap:12px; max-width:560px;">

          <div class="input">
            <i class="bi bi-person"></i>
            <input type="text" name="nama" placeholder="Nama"
              value="<?= esc(old('nama', $row['nama'] ?? '')); ?>" required>
          </div>

          <div class="input">
            <i class="bi bi-envelope"></i>
            <input type="email" name="email" placeholder="Email"
              value="<?= esc(old('email', $row['email'] ?? '')); ?>" required>
          </div>

          <div class="input">
            <i class="bi bi-telephone"></i>
            <input type="text" name="no_hp" placeholder="No HP"
              value="<?= esc(old('no_hp', $row['no_hp'] ?? '')); ?>">
          </div>

          <?php $roleVal = old('role', $row['role'] ?? 'user'); ?>
          <select class="select" name="role" required>
            <option value="user"  <?= ($roleVal === 'user') ? 'selected' : ''; ?>>User</option>
            <option value="admin" <?= ($roleVal === 'admin') ? 'selected' : ''; ?>>Admin</option>
          </select>

          <?php
            // status_akun: aktif / nonaktif
            // default create: aktif
            $statusVal = old('status_akun', $row['status_akun'] ?? 'aktif');
          ?>
          <select class="select" name="status_akun" required>
            <option value="aktif" <?= ($statusVal === 'aktif') ? 'selected' : ''; ?>>Aktif</option>
            <option value="nonaktif" <?= ($statusVal === 'nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
          </select>

          <div class="input">
            <i class="bi bi-key"></i>
            <input type="password" name="password"
              placeholder="<?= ($mode === 'edit') ? 'Password baru (opsional)' : 'Password'; ?>"
              <?= ($mode === 'edit') ? '' : 'required'; ?>>
          </div>

          <div style="display:flex; gap:10px;">
            <button class="btn btn--primary" type="submit">
              <i class="bi bi-save"></i> Simpan
            </button>
            <a class="btn btn--ghost" href="<?= site_url('admin/users'); ?>">Batal</a>
          </div>

        </div>
      </form>

    </div>
  </section>
</div>

<?= $this->endSection(); ?>
