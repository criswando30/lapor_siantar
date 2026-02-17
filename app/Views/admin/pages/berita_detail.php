<?= $this->extend('admin/layouts/admin'); ?>
<?= $this->section('content'); ?>

<div class="page">
  <div class="page__header">
    <div>
      <h1 class="page__title">Detail Berita</h1>
      <p class="page__subtitle"><?= esc($row['judul'] ?? ''); ?></p>
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">
      <a class="btn btn--ghost" href="<?= site_url('admin/berita'); ?>">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
      <a class="btn btn--primary" href="<?= site_url('admin/berita/'.$row['id'].'/edit'); ?>">
        <i class="bi bi-pencil"></i> Edit
      </a>
    </div>
  </div>

  <section class="panel">
    <div class="panel__body" style="max-width:920px;">

      <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
        <?php
          $isPub = (($row['status'] ?? '') === 'publish');
          $cls = $isPub ? 'badge--ok' : 'badge--warn';
          $lbl = $isPub ? 'PUBLISH' : 'DRAFT';
        ?>
        <span class="badge <?= $cls; ?>"><?= $lbl; ?></span>

        <div style="color:var(--muted); font-size:12px;">
          <strong>Slug:</strong> <?= esc($row['slug'] ?? ''); ?>
        </div>

        <div style="color:var(--muted); font-size:12px;">
          <strong>Publish:</strong> <?= esc($row['tanggal_publish'] ?? '-'); ?>
        </div>
      </div>

      <?php if (!empty($row['gambar'])): ?>
        <div style="margin-top:12px;">
          <img src="<?= base_url($row['gambar']); ?>" alt="gambar"
            style="width:100%; max-height:380px; object-fit:cover; border-radius:14px; border:1px solid var(--border);">
        </div>
      <?php endif; ?>

      <?php if (!empty($row['ringkas'])): ?>
        <div style="margin-top:14px; padding:12px; background:#f8fafc; border:1px solid var(--border); border-radius:12px;">
          <div style="font-weight:900; margin-bottom:6px;">Ringkasan</div>
          <div style="color:var(--text); font-size:13px; line-height:1.5;">
            <?= esc($row['ringkas']); ?>
          </div>
        </div>
      <?php endif; ?>

      <div style="margin-top:14px;">
        <div style="font-weight:900; margin-bottom:8px;">Isi Berita</div>
        <div style="font-size:13px; line-height:1.7; color:var(--text); white-space:pre-wrap;">
          <?= esc($row['isi'] ?? ''); ?>
        </div>
      </div>

    </div>
  </section>
</div>

<?= $this->endSection(); ?>
