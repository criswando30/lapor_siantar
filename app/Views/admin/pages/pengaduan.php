<?= $this->extend('admin/layouts/admin'); ?>
<?= $this->section('content'); ?>

<?php
$from = $filters['from'] ?? '';
$to = $filters['to'] ?? '';
$dateActive = ($from !== '' || $to !== '');
?>

<div class="page">

    <!-- Header -->
    <div class="page__header">
        <div>
            <h1 class="page__title">Manajemen Pengaduan</h1>
            <p class="page__subtitle">Pusat kontrol pemantauan aduan masyarakat terintegrasi.</p>
        </div>
    </div>

    <!-- KPI -->
    <section class="kpi">
        <div class="kpi__card kpi__card--red">
            <div class="kpi__label">TOTAL LAPORAN</div>
            <div class="kpi__value"><?= esc($stats['total'] ?? 0); ?></div>
            <div class="kpi__bar"></div>
        </div>

        <div class="kpi__card kpi__card--orange">
            <div class="kpi__label">MENUNGGU VERIFIKASI</div>
            <div class="kpi__value"><?= esc($stats['menunggu'] ?? 0); ?></div>
            <div class="kpi__bar"></div>
        </div>

        <div class="kpi__card kpi__card--blue">
            <div class="kpi__label">SEDANG DIPROSES</div>
            <div class="kpi__value"><?= esc($stats['diproses'] ?? 0); ?></div>
            <div class="kpi__bar"></div>
        </div>

        <div class="kpi__card kpi__card--green">
            <div class="kpi__label">LAPORAN SELESAI</div>
            <div class="kpi__value"><?= esc($stats['selesai'] ?? 0); ?></div>
            <div class="kpi__bar"></div>
        </div>
    </section>

    <!-- Panel Tabel -->
    <section class="panel">
        <div class="panel__body">

            <!-- Toolbar Filter -->
            <form class="table-tools" method="get" action="<?= current_url(); ?>">
                <div class="table-tools__left">
                    <div class="input input--search">
                        <i class="bi bi-search"></i>
                        <input type="text" name="q" value="<?= esc($filters['q'] ?? ''); ?>"
                            placeholder="Cari kode tiket, pelapor, atau kata kunci..." autocomplete="off">
                    </div>

                    <?php if ($dateActive): ?>
                        <div class="filter-chip" title="Filter tanggal aktif">
                            <i class="bi bi-calendar3"></i>
                            <span>
                                <?= esc($from ?: '...'); ?> → <?= esc($to ?: '...'); ?>
                            </span>
                            <a class="chip-x" href="<?= current_url(); ?>?<?= http_build_query(array_filter([
                                  'q' => $filters['q'] ?? '',
                                  'status' => $filters['status'] ?? '',
                                  'kategori_id' => $filters['kategori_id'] ?? '',
                              ])); ?>" aria-label="Hapus filter tanggal">✕</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="table-tools__right">
                    <select class="select" name="status">
                        <option value="">Semua Status</option>
                        <option value="laporan_diterima" <?= (($filters['status'] ?? '') === 'laporan_diterima') ? 'selected' : ''; ?>>Diterima</option>
                        <option value="diverifikasi" <?= (($filters['status'] ?? '') === 'diverifikasi') ? 'selected' : ''; ?>>Diverifikasi</option>
                        <option value="dalam_proses" <?= (($filters['status'] ?? '') === 'dalam_proses') ? 'selected' : ''; ?>>Dalam Proses</option>
                        <option value="selesai" <?= (($filters['status'] ?? '') === 'selesai') ? 'selected' : ''; ?>>
                            Selesai</option>
                    </select>

                    <select class="select" name="kategori_id">
                        <option value="">Semua Kategori</option>
                        <?php foreach (($kategoriList ?? []) as $k): ?>
                            <option value="<?= esc($k['id']); ?>" <?= ((string) ($filters['kategori_id'] ?? '') === (string) $k['id']) ? 'selected' : ''; ?>>
                                <?= esc($k['nama_kategori']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="date-filter" id="dateFilter">
                        <button class="btn btn--ghost <?= $dateActive ? 'is-active' : '' ?>" type="button" id="btnDate"
                            aria-expanded="false">
                            <i class="bi bi-calendar3"></i>
                            <span>Filter Tanggal</span>
                        </button>

                        <div class="date-popover" id="datePopover" hidden>
                            <div class="date-popover__head">
                                <div class="date-popover__title">Rentang Tanggal</div>
                                <button type="button" class="date-popover__close" id="btnCloseDate"
                                    aria-label="Tutup">✕</button>
                            </div>

                            <div class="date-popover__body">
                                <div class="date-grid">
                                    <div class="date-field">
                                        <label class="date-label">Dari</label>
                                        <input class="date-input" type="date" name="from" value="<?= esc($from) ?>">
                                    </div>

                                    <div class="date-field">
                                        <label class="date-label">Sampai</label>
                                        <input class="date-input" type="date" name="to" value="<?= esc($to) ?>">
                                    </div>
                                </div>

                                <div class="date-actions">
                                    <a class="btn btn--ghost btn--sm" href="<?= current_url(); ?>?<?= http_build_query(array_filter([
                                          'q' => $filters['q'] ?? '',
                                          'status' => $filters['status'] ?? '',
                                          'kategori_id' => $filters['kategori_id'] ?? '',
                                      ])); ?>">Reset Tanggal</a>

                                    <button type="button" class="btn btn--ghost btn--sm"
                                        id="btnCloseDate2">Tutup</button>
                                </div>


                                <small class="date-hint">Tip: klik di luar untuk menutup popover.</small>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn--primary" type="submit">
                        <i class="bi bi-funnel"></i>
                        <span>Terapkan</span>
                    </button>

                    <a class="btn btn--ghost" href="<?= current_url(); ?>">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>

            <!-- Table -->
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 170px;">ID &amp; TANGGAL</th>
                            <th style="width: 220px;">IDENTITAS PELAPOR</th>
                            <th>KATEGORI &amp;<br>DESKRIPSI LAPORAN</th>
                            <th style="width: 160px;">STATUS<br>TERKINI</th>
                            <th style="width: 120px;">AKSI</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($rows)): ?>
                            <?php foreach ($rows as $r): ?>
                                <tr>
                                    <td>
                                        <div class="cell-id">
                                            <div class="cell-id__main"><?= esc($r['kode']); ?></div>
                                            <div class="cell-id__sub"><?= esc($r['tanggal']); ?></div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="cell-user">
                                            <div class="avatar"></div>
                                            <div class="cell-user__meta">
                                                <div class="cell-user__name"><?= esc($r['pelapor']); ?></div>
                                                <div class="cell-user__sub"><?= esc($r['kontak'] ?? ''); ?></div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="cell-desc cell-desc--center">
                                            <div class="badge badge--cat"><?= esc($r['kategori']); ?></div>
                                            <div class="cell-desc__text"><?= esc($r['deskripsi']); ?></div>
                                        </div>
                                    </td>

                                    <td class="td-center">
                                        <?php
                                        $st = $r['status'];
                                        $map = [
                                            'laporan_diterima' => ['cls' => 'badge--warn', 'label' => 'Diterima'],
                                            'diverifikasi' => ['cls' => 'badge--info', 'label' => 'Diverifikasi'],
                                            'dalam_proses' => ['cls' => 'badge--info', 'label' => 'Dalam Proses'],
                                            'selesai' => ['cls' => 'badge--ok', 'label' => 'Selesai'],
                                        ];
                                        $cls = $map[$st]['cls'] ?? 'badge--warn';
                                        $label = $map[$st]['label'] ?? $st;
                                        ?>
                                        <span class="badge <?= $cls; ?>"><?= esc($label); ?></span>
                                    </td>

                                    <td>
                                        <a class="btn btn--sm btn--ghost"
                                            href="<?= base_url('admin/pengaduan/' . $r['id']); ?>">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="empty">
                                    <div class="empty__title">Belum ada data pengaduan</div>
                                    <div class="empty__subtitle">Gunakan filter di atas untuk menampilkan data.</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pager (kalau ada) -->
            <?php if (isset($pager) && $pager->getPageCount('laporan_admin') > 1): ?>
                <div class="pager-wrap" style="margin-top:12px;">
                    <?= $pager->links('laporan_admin', 'default_full') ?>
                </div>
            <?php endif; ?>

        </div>
    </section>
</div>

<script>
    (function () {
        const wrap = document.getElementById('dateFilter');
        const btn = document.getElementById('btnDate');
        const pop = document.getElementById('datePopover');
        const closeBtn = document.getElementById('btnCloseDate');
        if (!wrap || !btn || !pop) return;

        function openPop() {
            pop.hidden = false;
            btn.setAttribute('aria-expanded', 'true');
        }
        function closePop() {
            pop.hidden = true;
            btn.setAttribute('aria-expanded', 'false');
        }

        btn.addEventListener('click', () => pop.hidden ? openPop() : closePop());
        if (closeBtn) closeBtn.addEventListener('click', closePop);

        document.addEventListener('click', (e) => { if (!wrap.contains(e.target)) closePop(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closePop(); });

        const hasFrom = new URLSearchParams(window.location.search).get('from');
        const hasTo = new URLSearchParams(window.location.search).get('to');
        if (hasFrom || hasTo) openPop();
        const closeBtn2 = document.getElementById('btnCloseDate2');
        if (closeBtn2) closeBtn2.addEventListener('click', closePop);

    })();
</script>

<?= $this->endSection(); ?>