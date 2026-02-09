<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="status-page">
  <div class="container status-page__container">

    <!-- Header -->
    <div class="status-head">
      <h1 class="status-head__title">Lacak Aduan Anda</h1>
      <p class="status-head__desc">
        Pantau perkembangan laporan pelanggan Anda secara transparan dan real-time
      </p>
    </div>

    <!-- Search box -->
    <div class="status-card">
      <form action="<?= site_url('status') ?>" method="get" class="status-form">
        <div class="status-form__field">
          <input
            name="kode"
            type="text"
            value="<?= esc($_GET['kode'] ?? '') ?>"
            placeholder="Masukkan Nomor Tiket"
            class="status-input"
            required
          />
          <span class="status-input__icon" aria-hidden="true">
            <i class="bi bi-search"></i>
          </span>
        </div>

        <button type="submit" class="btn btn--primary status-btn">
          Cari Status Laporan
        </button>
      </form>
    </div>

    <?php $statusError = session()->getFlashdata('status_error'); ?>
    <?php if (!empty($statusError)): ?>
      <div class="status-alert status-alert--danger">
        <?= esc($statusError) ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($laporan) && is_array($laporan)): ?>
      <!-- Detail title -->
      <div class="status-detail-title">
        <span class="status-detail-title__icon" aria-hidden="true">
          <i class="bi bi-file-earmark-text"></i>
        </span>
        <h2 class="status-detail-title__text">
          Detail Laporan : <?= esc($laporan['kode'] ?? '-') ?>
        </h2>
      </div>

      <!-- Detail card -->
      <div class="status-card status-card--pad">
        <div class="status-detail">
          <div class="status-detail__left">
            <div class="status-meta-label">Judul Pengaduan</div>
            <div class="status-detail__headline">
              <?= esc($laporan['judul'] ?? '-') ?>
            </div>

            <div class="status-meta-grid">
              <div>
                <div class="status-meta-label">Kategori</div>
                <div class="status-meta-value">
                  <?= esc($laporan['kategori'] ?? '-') ?>
                </div>
              </div>
              <div>
                <div class="status-meta-label">Tanggal Masuk</div>
                <div class="status-meta-value">
                  <?= esc($laporan['tanggal_masuk'] ?? ($laporan['created_at'] ?? '-')) ?>
                </div>
              </div>
            </div>
          </div>

          <?php
            $status = strtolower(trim($laporan['status'] ?? ''));
            $badgeClass = 'badge badge--process';
            if (in_array($status, ['diterima','pending'])) $badgeClass = 'badge badge--pending';
            if (in_array($status, ['diproses','proses']))  $badgeClass = 'badge badge--process';
            if (in_array($status, ['selesai','closed']))   $badgeClass = 'badge badge--done';
            if (in_array($status, ['ditolak','reject']))   $badgeClass = 'badge badge--reject';
          ?>
          <div class="status-detail__right">
            <div class="status-badge-label">STATUS SAAT INI</div>
            <div class="<?= $badgeClass ?>">
              <span class="badge__dot"></span>
              <?= esc($laporan['status_label'] ?? ($laporan['status'] ?? '-')) ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Timeline card -->
      <div class="status-card status-card--pad">
        <?php $timeline = $laporan['timeline'] ?? []; ?>

        <div class="timeline">
          <div class="timeline__line" aria-hidden="true"></div>

          <div class="timeline__list">
            <?php foreach ($timeline as $t): ?>
              <?php
                $state = $t['state'] ?? 'todo';

                // circle
                $circleClass = 'tl-circle tl-circle--todo';
                if ($state === 'done')   $circleClass = 'tl-circle tl-circle--done';
                if ($state === 'active') $circleClass = 'tl-circle tl-circle--active';

                // text
                $titleClass = 'tl-title';
                $timeClass  = 'tl-time';
                $noteClass  = 'tl-note';
                if ($state === 'todo') {
                  $titleClass .= ' tl-title--todo';
                  $timeClass  .= ' tl-time--todo';
                  $noteClass  .= ' tl-note--todo';
                }

                $icon = $t['icon'] ?? 'check'; // check | process | finish
                $iconHtml = '<i class="bi bi-check-lg"></i>';
                if ($icon === 'process') $iconHtml = '<i class="bi bi-clipboard-check"></i>';
                if ($icon === 'finish')  $iconHtml = '<i class="bi bi-check-circle"></i>';
              ?>

              <div class="timeline-item">
                <div class="<?= $circleClass ?>">
                  <?= $iconHtml ?>
                </div>

                <div class="timeline-item__content">
                  <div class="<?= $titleClass ?>">
                    <?= esc($t['title'] ?? '-') ?>
                  </div>
                  <div class="<?= $timeClass ?>">
                    <?= esc($t['time'] ?? '-') ?>
                  </div>

                  <div class="<?= $noteClass ?>">
                    <?= esc($t['note'] ?? '-') ?>
                  </div>
                </div>
              </div>

            <?php endforeach; ?>
          </div>

        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<?= $this->endSection() ?>
