<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-10 bg-[#f6f8fb]">
  <div class="container mx-auto px-5 max-w-5xl">

    <!-- Header -->
    <div class="mb-5">
      <h1 class="text-lg md:text-xl font-extrabold text-gray-900">Lacak Aduan Anda</h1>
      <p class="mt-1 text-sm text-gray-500">
        Pantau perkembangan laporan pelanggan Anda secara transparan dan real-time
      </p>
    </div>

    <!-- Search box -->
    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
      <form action="<?= site_url('status') ?>" method="get" class="flex flex-col md:flex-row gap-3">
        <div class="flex-1 relative">
          <input
            name="kode"
            type="text"
            value="<?= esc($_GET['kode'] ?? '') ?>"
            placeholder="Masukkan Nomor Tiket"
            class="w-full bg-gray-100 border border-gray-200 rounded-md py-3 pl-4 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
            required
          />
          <span class="absolute right-4 top-1/2 -translate-y-1/2 text-primary">
            <i class="bi bi-search"></i>
          </span>
        </div>

        <button
          type="submit"
          class="px-6 py-3 rounded-md bg-primary hover:bg-secondary text-white text-sm font-extrabold transition"
        >
          Cari Status Laporan
        </button>
      </form>
    </div>

    <?php $statusError = session()->getFlashdata('status_error'); ?>
    <?php if (!empty($statusError)): ?>
      <div class="mt-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
        <?= esc($statusError) ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($laporan) && is_array($laporan)): ?>
      <!-- Detail title -->
      <div class="mt-6 flex items-center gap-2">
        <span class="text-primary text-lg">
          <i class="bi bi-file-earmark-text"></i>
        </span>
        <h2 class="text-base md:text-lg font-extrabold text-gray-900">
          Detail Laporan : <?= esc($laporan['kode'] ?? '-') ?>
        </h2>
      </div>

      <!-- Detail card -->
      <div class="mt-3 bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <div class="text-xs text-gray-500 font-semibold">Judul Pengaduan</div>
            <div class="mt-1 text-lg font-extrabold text-gray-900">
              <?= esc($laporan['judul'] ?? '-') ?>
            </div>

            <div class="mt-3 grid grid-cols-2 gap-8 text-sm">
              <div>
                <div class="text-xs text-gray-500 font-semibold">Kategori</div>
                <div class="mt-1 text-gray-800">
                  <?= esc($laporan['kategori'] ?? '-') ?>
                </div>
              </div>
              <div>
                <div class="text-xs text-gray-500 font-semibold">Tanggal Masuk</div>
                <div class="mt-1 text-gray-800">
                  <?= esc($laporan['tanggal_masuk'] ?? ($laporan['created_at'] ?? '-')) ?>
                </div>
              </div>
            </div>
          </div>

          <?php
            // warna badge status (utama: biru untuk diproses)
            $status = strtolower(trim($laporan['status'] ?? ''));
            $badge = 'bg-blue-100 text-blue-700';
            if (in_array($status, ['diterima','pending'])) $badge = 'bg-yellow-100 text-yellow-800';
            if (in_array($status, ['diproses','proses']))  $badge = 'bg-blue-100 text-blue-700';
            if (in_array($status, ['selesai','closed']))   $badge = 'bg-green-100 text-green-800';
            if (in_array($status, ['ditolak','reject']))   $badge = 'bg-red-100 text-red-800';
          ?>
          <div class="md:text-right">
            <div class="text-[11px] text-gray-500 font-extrabold tracking-wide">STATUS SAAT INI</div>
            <div class="mt-2 inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-extrabold <?= $badge ?>">
              <span class="w-2 h-2 rounded-full bg-current opacity-60"></span>
              <?= esc($laporan['status_label'] ?? ($laporan['status'] ?? '-')) ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Timeline card -->
      <div class="mt-4 bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
        <?php $timeline = $laporan['timeline'] ?? []; ?>

        <div class="relative">
          <!-- garis vertikal -->
          <div class="absolute left-8 top-2 bottom-2 w-px bg-gray-200"></div>

          <div class="space-y-6">
            <?php foreach ($timeline as $t): ?>
              <?php
                // state: done / active / todo
                $state = $t['state'] ?? 'todo';

                // styling icon circle
                // done: biru solid, active: biru outline, todo: abu outline
                $circle = 'bg-gray-100 text-gray-500 border-gray-300';
                if ($state === 'done')   $circle = 'bg-primary text-white border-primary';
                if ($state === 'active') $circle = 'bg-white text-primary border-primary';

                // text style
                $titleCls = 'text-gray-900';
                $timeCls  = 'text-gray-500';
                $noteCls  = 'text-gray-700';
                $noteBg   = 'bg-gray-50';
                if ($state === 'todo') {
                  $titleCls = 'text-gray-400';
                  $timeCls  = 'text-gray-400';
                  $noteCls  = 'text-gray-400';
                  $noteBg   = 'bg-gray-100';
                }

                // icon type (pakai bootstrap icons biar mirip)
                $icon = $t['icon'] ?? 'check'; // check | process | finish
                $iconHtml = '<i class="bi bi-check-lg text-base"></i>';
                if ($icon === 'process') $iconHtml = '<i class="bi bi-clipboard-check text-base"></i>';
                if ($icon === 'finish')  $iconHtml = '<i class="bi bi-check-circle text-base"></i>';
              ?>

              <div class="relative pl-16">
                <!-- icon -->
                <div class="absolute left-4 top-0.5 w-9 h-9 rounded-full border flex items-center justify-center <?= $circle ?>">
                  <?= $iconHtml ?>
                </div>

                <!-- content -->
                <div>
                  <div class="text-sm font-extrabold <?= $titleCls ?>">
                    <?= esc($t['title'] ?? '-') ?>
                  </div>
                  <div class="mt-0.5 text-xs <?= $timeCls ?>">
                    <?= esc($t['time'] ?? '-') ?>
                  </div>

                  <div class="mt-2 rounded-lg px-4 py-3 text-sm border border-gray-100 <?= $noteBg ?> <?= $noteCls ?>">
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
