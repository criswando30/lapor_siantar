<?= $this->extend('admin/layouts/admin'); ?>
<?= $this->section('content'); ?>

<div class="page">
  <div class="page__header">
    <div>
      <h1 class="page__title"><?= esc($title ?? 'Form Berita'); ?></h1>
      <p class="page__subtitle">Lengkapi data berita, lalu simpan.</p>
    </div>

    <a class="btn btn--ghost" href="<?= site_url('admin/berita'); ?>">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>
  </div>

  <section class="panel">
    <div class="panel__body">

      <?php $isEdit = (($mode ?? '') === 'edit'); ?>

      <form method="post"
        action="<?= $isEdit ? site_url('admin/berita/'.$row['id'].'/update') : site_url('admin/berita/store'); ?>"
        enctype="multipart/form-data">
        <?= csrf_field(); ?>

        <div style="display:grid; gap:12px; max-width:760px;">

          <div class="input">
            <i class="bi bi-card-text"></i>
            <input type="text" name="judul" placeholder="Judul berita"
              value="<?= esc(old('judul', $row['judul'] ?? '')); ?>">
          </div>

          <div class="input">
            <i class="bi bi-journal-text"></i>
            <input type="text" name="ringkas" placeholder="Ringkasan (opsional)"
              value="<?= esc(old('ringkas', $row['ringkas'] ?? '')); ?>">
          </div>

          <div class="input" style="align-items:flex-start;">
            <i class="bi bi-textarea-t"></i>
            <textarea name="isi" rows="10" style="width:100%; border:0; outline:0; background:transparent; font-size:13px;"
              placeholder="Isi berita"><?= esc(old('isi', $row['isi'] ?? '')); ?></textarea>
          </div>

          <div style="display:grid; gap:8px;">
            <label style="font-weight:900; font-size:12px; color:var(--muted);">Gambar (opsional, max 2MB)</label>
            <input class="select" style="height:auto; padding:10px 12px;" type="file" name="gambar" accept="image/*">

            <?php if (!empty($row['gambar'])): ?>
              <div style="display:flex; gap:10px; align-items:center;">
                <img src="<?= base_url($row['gambar']); ?>" alt="gambar" style="width:90px; height:60px; object-fit:cover; border-radius:10px; border:1px solid var(--border);">
                <div style="font-size:12px; color:var(--muted);"><?= esc($row['gambar']); ?></div>
              </div>
            <?php endif; ?>
          </div>

          <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <?php $st = old('status', $row['status'] ?? 'draft'); ?>
            <select class="select" name="status">
              <option value="draft" <?= ($st === 'draft') ? 'selected' : ''; ?>>Draft</option>
              <option value="publish" <?= ($st === 'publish') ? 'selected' : ''; ?>>Publish</option>
            </select>

            <div style="display:flex; gap:10px;">
              <button class="btn btn--primary" type="submit">
                <i class="bi bi-save"></i> Simpan
              </button>
              <a class="btn btn--ghost" href="<?= site_url('admin/berita'); ?>">Batal</a>
            </div>
          </div>

          <small style="color:var(--muted); font-size:12px;">
            Catatan: jika status Publish, sistem akan mengisi tanggal_publish otomatis.
          </small>

        </div>
      </form>

    </div>
  </section>
</div>

<?= $this->endSection(); ?>
