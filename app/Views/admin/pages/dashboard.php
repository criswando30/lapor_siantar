<?= $this->extend('admin/layouts//admin') ?>

<?= $this->section('content') ?>

<div class="page">
  <div class="page__header">
    <div>
      <h1 class="page__title">Ringkasan Operasional</h1>
      <p class="page__subtitle">Pusat kontrol pemantauan aduan masyarakat terintegrasi.</p>
    </div>

    <button class="chip" type="button">
      <span class="chip__icon">📅</span>
      <span>30 Hari Terakhir</span>
    </button>
  </div>

  <div class="kpi">
    <div class="kpi__card kpi__card--red">
      <div class="kpi__label">TOTAL LAPORAN</div>
      <div class="kpi__value"><?= esc($stats['total']) ?></div>
      <div class="kpi__bar"></div>
    </div>

    <div class="kpi__card kpi__card--orange">
      <div class="kpi__label">MENUNGGU VERIFIKASI</div>
      <div class="kpi__value"><?= esc($stats['verifikasi']) ?></div>
      <div class="kpi__bar"></div>
    </div>

    <div class="kpi__card kpi__card--blue">
      <div class="kpi__label">SEDANG DIPROSES</div>
      <div class="kpi__value"><?= esc($stats['diproses']) ?></div>
      <div class="kpi__bar"></div>
    </div>

    <div class="kpi__card kpi__card--green">
      <div class="kpi__label">LAPORAN SELESAI</div>
      <div class="kpi__value"><?= esc($stats['selesai']) ?></div>
      <div class="kpi__bar"></div>
    </div>
  </div>

  <div class="grid">
    <section class="panel panel--wide">
      <div class="panel__head">
        <div>
          <h2 class="panel__title">Trend Pelaporan Harian</h2>
          <p class="panel__desc">Fluktuasi jumlah laporan selama 30 hari terakhir</p>
        </div>
        <div class="legend">
          <span class="dot"></span>
          <span>Volume Laporan</span>
        </div>
      </div>

      <div class="panel__body panel__body--empty">
        <!-- nanti tempat chart -->
      </div>
    </section>

    <section class="panel">
      <div class="panel__head">
        <div>
          <h2 class="panel__title panel__title--blue">Aktivitas Laporan Terbaru</h2>
          <p class="panel__desc">Laporan terbaru yang belum di proses.</p>
        </div>
      </div>

      <div class="panel__body">
        <?php foreach ($aktivitas as $a): ?>
          <div class="activity">
            <div class="activity__top">
              <div class="activity__title"><?= esc($a['judul']) ?></div>
              <div class="activity__time"><?= esc($a['waktu']) ?></div>
            </div>
            <div class="activity__meta">
              <span class="activity__status"><?= esc($a['status']) ?></span>
              <a class="activity__link" href="#">Detail ›</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </div>
</div>

<?= $this->endSection() ?>
