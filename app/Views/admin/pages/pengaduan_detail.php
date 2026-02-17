<?= $this->extend('admin/layouts/admin'); ?>
<?= $this->section('content'); ?>

<div class="page">

  <div class="page__header">
    <div>
      <h1 class="page__title">Detail Pengaduan</h1>
      <p class="page__subtitle">Kode Tiket: <strong><?= esc($laporan['kode_tiket']) ?></strong></p>
    </div>
    <a class="btn btn--ghost" href="<?= site_url('admin/pengaduan') ?>">← Kembali</a>
  </div>

  <?php if (session()->getFlashdata('login_success')): ?>
    <div class="alert alert--success"><?= session()->getFlashdata('login_success') ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('login_error')): ?>
    <div class="alert alert--danger"><?= session()->getFlashdata('login_error') ?></div>
  <?php endif; ?>

  <div class="grid" style="grid-template-columns: 1.2fr .8fr; gap: 1rem;">
    <!-- KIRI: info utama -->
    <section class="panel">
      <div class="panel__head">
        <div>
          <h2 class="panel__title">Informasi Laporan</h2>
          <p class="panel__desc">Detail kejadian dan lokasi</p>
        </div>
      </div>

      <div class="panel__body">

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: .75rem;">
          <div>
            <div class="muted">Pelapor</div>
            <div><strong><?= esc($laporan['pelapor'] ?? '-') ?></strong></div>
            <div class="muted"><?= esc($laporan['kontak'] ?? '-') ?><?= !empty($laporan['email']) ? ' • ' . esc($laporan['email']) : '' ?></div>
          </div>

          <div>
            <div class="muted">Status</div>
            <div><strong><?= esc($laporan['status']) ?></strong></div>
            <div class="muted">Dibuat: <?= esc($laporan['created_at'] ?? '-') ?></div>
          </div>

          <div>
            <div class="muted">Kategori</div>
            <div><strong><?= esc($laporan['kategori'] ?? '-') ?></strong></div>
            <div class="muted"><?= esc($laporan['subkategori'] ?? '-') ?></div>
          </div>

          <div>
            <div class="muted">Tanggal Kejadian</div>
            <div><strong><?= esc($laporan['tanggal_kejadian'] ?? '-') ?></strong></div>
            <div class="muted">Selesai: <?= esc($laporan['tanggal_selesai'] ?? '-') ?></div>
          </div>
        </div>

        <hr style="margin: 1rem 0; opacity:.2;">

        <div class="muted">Lokasi</div>
        <div><strong><?= esc($laporan['lokasi'] ?? '-') ?></strong></div>
        <div class="muted"><?= esc($laporan['alamat_lengkap'] ?? '-') ?></div>

        <div style="margin-top:.75rem; display:flex; gap:.75rem; flex-wrap:wrap;">
          <div class="chip">
            <span class="chip__icon">📍</span>
            <span>
              <?= esc($laporan['latitude'] ?? '-') ?>, <?= esc($laporan['longitude'] ?? '-') ?>
            </span>
          </div>
          <?php if (!empty($laporan['latitude']) && !empty($laporan['longitude'])): ?>
            <a class="btn btn--ghost btn--sm" target="_blank"
               href="https://www.google.com/maps?q=<?= urlencode($laporan['latitude'] . ',' . $laporan['longitude']) ?>">
              Buka di Maps
            </a>
          <?php endif; ?>
        </div>

        <hr style="margin: 1rem 0; opacity:.2;">

        <div class="muted">Deskripsi</div>
        <div style="white-space: pre-wrap;"><?= esc($laporan['deskripsi'] ?? '-') ?></div>

        <hr style="margin: 1rem 0; opacity:.2;">

        <div class="muted">Lampiran</div>
        <?php if (!empty($lampiran)): ?>
          <div style="display:grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap:.5rem; margin-top:.5rem;">
            <?php foreach ($lampiran as $f): ?>
              <?php $url = !empty($f['path_file']) ? base_url($f['path_file']) : '#'; ?>
              <a href="<?= esc($url) ?>" target="_blank" class="card" style="padding:.5rem; text-decoration:none;">
                <div style="font-size:.85rem; font-weight:600;"><?= esc($f['nama_file'] ?? 'file') ?></div>
                <div class="muted" style="font-size:.8rem;"><?= esc($f['created_at'] ?? '') ?></div>
              </a>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="muted">Tidak ada lampiran.</div>
        <?php endif; ?>

      </div>
    </section>

    <!-- KANAN: update status + timeline -->
    <section class="panel">
      <div class="panel__head">
        <div>
          <h2 class="panel__title panel__title--blue">Tindakan Petugas</h2>
          <p class="panel__desc">Ubah status dan catat keterangan</p>
        </div>
      </div>

      <div class="panel__body">
        <form method="post" action="<?= site_url('admin/pengaduan/' . (int)$laporan['id'] . '/status') ?>">
          <?= csrf_field() ?>

          <label class="muted" style="display:block; margin-bottom:.25rem;">Status Baru</label>
          <select name="status" class="select" required>
            <?php
              $opts = ['laporan_diterima'=>'Laporan Diterima','diverifikasi'=>'Diverifikasi','dalam_proses'=>'Dalam Proses','selesai'=>'Selesai'];
              foreach ($opts as $k => $label):
            ?>
              <option value="<?= esc($k) ?>" <?= ($laporan['status'] === $k) ? 'selected' : '' ?>>
                <?= esc($label) ?>
              </option>
            <?php endforeach; ?>
          </select>

          <label class="muted" style="display:block; margin:.75rem 0 .25rem;">Keterangan (opsional)</label>
          <textarea name="keterangan" class="form-control" rows="4" placeholder="Catatan penanganan..."><?= old('keterangan') ?></textarea>

          <div style="margin-top:.75rem;">
            <button class="btn btn--primary" type="submit">Simpan Perubahan</button>
          </div>
        </form>

        <hr style="margin: 1rem 0; opacity:.2;">

        <div class="muted">Timeline Status</div>
        <?php if (!empty($timeline)): ?>
          <div style="display:flex; flex-direction:column; gap:.75rem; margin-top:.5rem;">
            <?php foreach ($timeline as $t): ?>
              <div class="card" style="padding:.75rem;">
                <div style="display:flex; justify-content:space-between; gap:.75rem;">
                  <strong><?= esc($t['status']) ?></strong>
                  <span class="muted" style="font-size:.85rem;"><?= esc($t['created_at'] ?? '-') ?></span>
                </div>
                <?php if (!empty($t['keterangan'])): ?>
                  <div style="margin-top:.4rem; white-space:pre-wrap;"><?= esc($t['keterangan']) ?></div>
                <?php else: ?>
                  <div class="muted" style="margin-top:.4rem;">(tanpa keterangan)</div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="muted" style="margin-top:.5rem;">Belum ada riwayat status.</div>
        <?php endif; ?>

      </div>
    </section>
  </div>

</div>

<?= $this->endSection(); ?>
