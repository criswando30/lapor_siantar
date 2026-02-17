<?= $this->extend('admin/layouts/admin'); ?>
<?= $this->section('content'); ?>

<div class="page">
  <div class="page__header">
    <div>
      <h1 class="page__title">Laporan Analitik</h1>
      <p class="page__subtitle">Ringkasan & rekap pengaduan berdasarkan periode.</p>
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">
      <a class="btn btn--ghost" href="<?= site_url('admin/analitik/export-pdf?' . http_build_query($filters)); ?>">
        <i class="bi bi-filetype-pdf"></i> Export PDF
      </a>
      <a class="btn btn--primary" href="<?= site_url('admin/analitik/export-excel?' . http_build_query($filters)); ?>">
        <i class="bi bi-file-earmark-excel"></i> Export Excel
      </a>
    </div>
  </div>

  <!-- Filter -->
  <section class="panel">
    <div class="panel__body">
      <form class="table-tools" method="get" action="<?= current_url(); ?>">
        <div class="table-tools__left" style="display:flex; gap:10px; flex-wrap:wrap;">
          <div class="input">
            <i class="bi bi-calendar"></i>
            <input type="date" name="from" value="<?= esc($filters['from'] ?? ''); ?>">
          </div>

          <div class="input">
            <i class="bi bi-calendar"></i>
            <input type="date" name="to" value="<?= esc($filters['to'] ?? ''); ?>">
          </div>

          <select class="select" name="status">
            <option value="">Semua Status</option>
            <?php
              $statusList = ['laporan_diterima','diverifikasi','dalam_proses','selesai'];
              foreach ($statusList as $st):
            ?>
              <option value="<?= $st; ?>" <?= (($filters['status'] ?? '') === $st) ? 'selected' : ''; ?>>
                <?= strtoupper(str_replace('_',' ', $st)); ?>
              </option>
            <?php endforeach; ?>
          </select>

          <select class="select" name="kategori_id">
            <option value="">Semua Kategori</option>
            <?php foreach (($kategoriList ?? []) as $k): ?>
              <option value="<?= $k['id']; ?>" <?= ((string)($filters['kategori_id'] ?? '') === (string)$k['id']) ? 'selected' : ''; ?>>
                <?= esc($k['nama_kategori']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="table-tools__right">
          <button class="btn btn--primary" type="submit">
            <i class="bi bi-funnel"></i> Terapkan
          </button>
          <a class="btn btn--ghost" href="<?= current_url(); ?>">
            <i class="bi bi-arrow-counterclockwise"></i> Reset
          </a>
        </div>
      </form>
    </div>
  </section>

  <!-- KPI -->
  <section class="kpi">
    <div class="kpi__card kpi__card--red">
      <div class="kpi__label">TOTAL</div>
      <div class="kpi__value"><?= esc($kpi['total'] ?? 0); ?></div>
      <div class="kpi__bar"></div>
    </div>
    <div class="kpi__card kpi__card--orange">
      <div class="kpi__label">DITERIMA</div>
      <div class="kpi__value"><?= esc($kpi['laporan_diterima'] ?? 0); ?></div>
      <div class="kpi__bar"></div>
    </div>
    <div class="kpi__card kpi__card--blue">
      <div class="kpi__label">DIVERIFIKASI</div>
      <div class="kpi__value"><?= esc($kpi['diverifikasi'] ?? 0); ?></div>
      <div class="kpi__bar"></div>
    </div>
    <div class="kpi__card kpi__card--green">
      <div class="kpi__label">SELESAI</div>
      <div class="kpi__value"><?= esc($kpi['selesai'] ?? 0); ?></div>
      <div class="kpi__bar"></div>
    </div>
  </section>

  <section class="panel">
    <div class="panel__body">

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
        <!-- Trend sederhana -->
        <div style="border:1px solid var(--border); border-radius:14px; padding:12px;">
          <div style="font-weight:900; margin-bottom:10px;">Tren Laporan (Harian)</div>
          <div style="max-height:220px; overflow:auto;">
            <table class="table">
              <thead><tr><th>Tanggal</th><th style="width:120px;">Total</th></tr></thead>
              <tbody>
                <?php foreach (($trend ?? []) as $t): ?>
                  <tr>
                    <td><?= esc($t['tanggal']); ?></td>
                    <td style="text-align:center;"><?= (int)($t['total'] ?? 0); ?></td>
                  </tr>
                <?php endforeach; ?>
                <?php if (empty($trend)): ?>
                  <tr><td colspan="2" class="empty">Tidak ada data.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Rekap Kategori -->
        <div style="border:1px solid var(--border); border-radius:14px; padding:12px;">
          <div style="font-weight:900; margin-bottom:10px;">Rekap Per Kategori</div>
          <div style="max-height:220px; overflow:auto;">
            <table class="table">
              <thead><tr><th>Kategori</th><th style="width:120px;">Total</th></tr></thead>
              <tbody>
                <?php foreach (($byKategori ?? []) as $r): ?>
                  <tr>
                    <td><?= esc($r['nama_kategori'] ?? '-'); ?></td>
                    <td style="text-align:center;"><?= (int)($r['total'] ?? 0); ?></td>
                  </tr>
                <?php endforeach; ?>
                <?php if (empty($byKategori)): ?>
                  <tr><td colspan="2" class="empty">Tidak ada data.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div style="height:14px;"></div>

      <!-- Detail -->
      <div style="font-weight:900; margin-bottom:10px;">Detail Laporan (maks 2000 baris)</div>
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th style="width:80px;">ID</th>
              <th style="width:180px;">KODE</th>
              <th style="width:170px;">TANGGAL</th>
              <th style="width:150px;">STATUS</th>
              <th>KATEGORI</th>
              <th>SUB</th>
              <th>LOKASI</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (($details ?? []) as $d): ?>
              <tr>
                <td style="text-align:center;"><?= esc($d['id']); ?></td>
                <td><?= esc($d['kode_tiket']); ?></td>
                <td><?= esc($d['created_at']); ?></td>
                <td><?= esc(strtoupper(str_replace('_',' ', $d['status'] ?? ''))); ?></td>
                <td><?= esc($d['nama_kategori'] ?? '-'); ?></td>
                <td><?= esc($d['nama_subkategori'] ?? '-'); ?></td>
                <td><?= esc($d['lokasi'] ?? '-'); ?></td>
              </tr>
            <?php endforeach; ?>

            <?php if (empty($details)): ?>
              <tr><td colspan="7" class="empty">Tidak ada data.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </section>
</div>

<?= $this->endSection(); ?>
