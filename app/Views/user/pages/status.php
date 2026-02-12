<?= $this->extend('user/layouts/main') ?>
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
          <input name="kode" type="text" value="<?= esc($_GET['kode'] ?? '') ?>" placeholder="Masukkan Nomor Tiket"
            class="status-input" required />
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
                  <?php
                  $createdText = $laporan['created_at'] ?? '-';
                  if ($createdText !== '-' && $createdText !== '') {
                    $ts = strtotime($createdText);
                    if ($ts !== false)
                      $createdText = date('d M Y, H:i', $ts) . ' WIB';
                  }
                  ?>
                  <?= esc($createdText) ?>
                </div>
              </div>
            </div>
          </div>

          <?php
          $status = strtolower(trim($laporan['status'] ?? ''));
          $badgeClass = 'badge badge--process';
          if (in_array($status, ['diterima', 'pending'], true))
            $badgeClass = 'badge badge--pending';
          if (in_array($status, ['diproses', 'proses'], true))
            $badgeClass = 'badge badge--process';
          if (in_array($status, ['selesai', 'closed'], true))
            $badgeClass = 'badge badge--done';
          if (in_array($status, ['ditolak', 'reject'], true))
            $badgeClass = 'badge badge--reject';
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
      <!-- Timeline card (STYLE BARU) -->
      <div class="status-card status-card--pad">

        <div class="timeline2-head">
          <div class="timeline2-bar"></div>
          <div class="timeline2-title">LOG PERKEMBANGAN LAPORAN</div>
        </div>

        <?php $timeline = $laporan['timeline'] ?? []; ?>

        <div class="timeline2">
          <div class="timeline2-line" aria-hidden="true"></div>

          <?php foreach ($timeline as $idx => $t): ?>
            <?php
            $state = $t['state'] ?? 'todo'; // done | active | todo
        
            // badge kecil di samping judul
            $badgeText = '';
            $badgeClass = 't2-badge';
            if ($state === 'done') {
              $badgeText = 'SELESAI';
              $badgeClass .= ' t2-badge--done';
            } elseif ($state === 'active') {
              $badgeText = 'TAHAP SEKARANG';
              $badgeClass .= ' t2-badge--active';
            }

            // waktu
            $timeText = $t['time'] ?? '-';
            if ($timeText !== '-' && $timeText !== '') {
              $ts = strtotime($timeText);
              if ($ts !== false)
                $timeText = date('d F Y, H:i', $ts) . ' WIB';
            }

            // icon per step agar mirip contoh gambar (diamond)
            // step 0: panah, step 1: verifikasi, step 2: tim/penanganan, step 3: selesai
            $iconHtml = '<i class="bi bi-arrow-right"></i>';
            if ($idx === 1)
              $iconHtml = '<i class="bi bi-patch-check-fill"></i>';
            if ($idx === 2)
              $iconHtml = '<i class="bi bi-people-fill"></i>';
            if ($idx === 3)
              $iconHtml = '<i class="bi bi-check-lg"></i>';

            // style diamond mengikuti state + posisi (mirip gambar)
            $diamondClass = 't2-diamond t2-diamond--done';
            if ($state === 'active')
              $diamondClass = 't2-diamond t2-diamond--active';
            if ($state === 'todo')
              $diamondClass = 't2-diamond t2-diamond--todo';

            // box note
            $noteClass = 't2-note';
            if ($state === 'active')
              $noteClass .= ' t2-note--active';
            if ($state === 'todo')
              $noteClass .= ' t2-note--todo';

            // title warna mengikuti state
            $titleClass = 't2-step-title';
            if ($state === 'todo')
              $titleClass .= ' t2-step-title--todo';
            ?>
            <div class="t2-item t2-item--<?= esc($state) ?>">
              <div class="t2-left">
                <div class="<?= $diamondClass ?>">
                  <div class="t2-diamond__inner">
                    <?= $iconHtml ?>
                  </div>
                </div>
              </div>

              <div class="t2-content">
                <div class="t2-top">
                  <div class="<?= $titleClass ?>">
                    <?= esc($t['title'] ?? '-') ?>

                    <?php if ($badgeText !== ''): ?>
                      <span class="<?= $badgeClass ?>"><?= esc($badgeText) ?></span>
                    <?php endif; ?>
                  </div>

                  <div class="t2-time"><?= esc($timeText) ?></div>
                </div>

                <div class="<?= $noteClass ?>">
                  <?= esc($t['note'] ?? '-') ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      </div>


    <?php endif; ?>

  </div>
</section>

<?= $this->endSection() ?>