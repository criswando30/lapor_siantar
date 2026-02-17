<?= $this->extend('admin/layouts/admin'); ?>
<?= $this->section('content'); ?>

<div class="page">
  <div class="page__header">
    <div>
      <h1 class="page__title">Manajemen Berita</h1>
      <p class="page__subtitle">Kelola berita (draft/publish) untuk ditampilkan ke pengguna.</p>
    </div>

    <a class="btn btn--primary" href="<?= site_url('admin/berita/create'); ?>">
      <i class="bi bi-plus-lg"></i> Tambah Berita
    </a>
  </div>

  <section class="kpi">
    <div class="kpi__card kpi__card--red">
      <div class="kpi__label">TOTAL</div>
      <div class="kpi__value"><?= esc($stats['total'] ?? 0); ?></div>
      <div class="kpi__bar"></div>
    </div>
    <div class="kpi__card kpi__card--orange">
      <div class="kpi__label">DRAFT</div>
      <div class="kpi__value"><?= esc($stats['draft'] ?? 0); ?></div>
      <div class="kpi__bar"></div>
    </div>
    <div class="kpi__card kpi__card--green">
      <div class="kpi__label">PUBLISH</div>
      <div class="kpi__value"><?= esc($stats['publish'] ?? 0); ?></div>
      <div class="kpi__bar"></div>
    </div>
    <div class="kpi__card kpi__card--blue">
      <div class="kpi__label">TERBARU</div>
      <div class="kpi__value"><?= !empty($rows) ? esc($rows[0]['id']) : 0; ?></div>
      <div class="kpi__bar"></div>
    </div>
  </section>

  <section class="panel">
    <div class="panel__body">

      <form class="table-tools" method="get" action="<?= current_url(); ?>">
        <div class="table-tools__left">
          <div class="input">
            <i class="bi bi-search"></i>
            <input type="text" name="q" value="<?= esc($filters['q'] ?? ''); ?>"
              placeholder="Cari judul / ringkasan..." autocomplete="off">
          </div>
        </div>

        <div class="table-tools__right">
          <select class="select" name="status">
            <option value="">Semua Status</option>
            <option value="draft" <?= (($filters['status'] ?? '') === 'draft') ? 'selected' : ''; ?>>Draft</option>
            <option value="publish" <?= (($filters['status'] ?? '') === 'publish') ? 'selected' : ''; ?>>Publish</option>
          </select>

          <div class="input" style="max-width:170px;">
            <i class="bi bi-calendar3"></i>
            <input type="date" name="from" value="<?= esc($filters['from'] ?? ''); ?>" title="Dari tanggal publish">
          </div>

          <div class="input" style="max-width:170px;">
            <i class="bi bi-calendar3"></i>
            <input type="date" name="to" value="<?= esc($filters['to'] ?? ''); ?>" title="Sampai tanggal publish">
          </div>

          <button class="btn btn--primary" type="submit">
            <i class="bi bi-funnel"></i> Terapkan
          </button>

          <a class="btn btn--ghost" href="<?= current_url(); ?>">
            <i class="bi bi-arrow-counterclockwise"></i> Reset
          </a>
        </div>
      </form>

      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th style="width:70px; text-align:center;">ID</th>
              <th>JUDUL</th>
              <th style="width:140px; text-align:center;">STATUS</th>
              <th style="width:180px;">PUBLISH</th>
              <th style="width:260px; text-align:center;">AKSI</th>
            </tr>
          </thead>

          <tbody>
            <?php if (!empty($rows)): ?>
              <?php foreach ($rows as $r): ?>
                <tr>
                  <td style="text-align:center; font-weight:900;"><?= esc($r['id']); ?></td>

                  <td>
                    <div style="font-weight:900;"><?= esc($r['judul']); ?></div>
                    <div style="font-size:12px;color:var(--muted); margin-top:4px;">
                      <?= esc($r['slug'] ?? ''); ?>
                    </div>
                  </td>

                  <td style="text-align:center;">
                    <?php
                      $isPub = (($r['status'] ?? '') === 'publish');
                      $cls = $isPub ? 'badge--ok' : 'badge--warn';
                      $lbl = $isPub ? 'PUBLISH' : 'DRAFT';
                    ?>
                    <span class="badge <?= $cls; ?>"><?= $lbl; ?></span>
                  </td>

                  <td>
                    <?= esc($r['tanggal_publish'] ?? '-'); ?>
                  </td>

                  <td style="text-align:center;">
                    <a class="btn btn--sm btn--ghost" href="<?= site_url('admin/berita/' . $r['id']); ?>">
                      <i class="bi bi-eye"></i> Detail
                    </a>
                    <a class="btn btn--sm btn--ghost" href="<?= site_url('admin/berita/' . $r['id'] . '/edit'); ?>">
                      <i class="bi bi-pencil"></i> Edit
                    </a>

                    <form action="<?= site_url('admin/berita/' . $r['id'] . '/toggle'); ?>" method="post" style="display:inline;">
                      <?= csrf_field(); ?>
                      <button class="btn btn--sm btn--ghost" type="submit"
                        onclick="return confirm('Ubah status berita ini?')">
                        <i class="bi bi-power"></i> <?= $isPub ? 'Draft' : 'Publish'; ?>
                      </button>
                    </form>

                    <form action="<?= site_url('admin/berita/' . $r['id'] . '/delete'); ?>" method="post" style="display:inline;">
                      <?= csrf_field(); ?>
                      <button class="btn btn--sm btn--ghost" type="submit"
                        onclick="return confirm('Hapus berita ini?')">
                        <i class="bi bi-trash"></i> Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="empty">
                  <div class="empty__title">Belum ada data berita</div>
                  <div class="empty__subtitle">Klik "Tambah Berita" untuk membuat berita baru.</div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if ($pager && $pager->getPageCount('berita_admin') > 1): ?>
        <div style="margin-top:14px;">
          <?= $pager->links('berita_admin', 'default_full'); ?>
        </div>
      <?php endif; ?>

    </div>
  </section>
</div>

<?= $this->endSection(); ?>
