<?= $this->extend('admin/layouts/admin'); ?>

<?= $this->section('content'); ?>
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
            <div class="kpi__value"><?= esc($kpi['total'] ?? 0); ?></div>
            <div class="kpi__bar"></div>
        </div>

        <div class="kpi__card kpi__card--orange">
            <div class="kpi__label">MENUNGGU VERIFIKASI</div>
            <div class="kpi__value"><?= esc($kpi['menunggu'] ?? 0); ?></div>
            <div class="kpi__bar"></div>
        </div>

        <div class="kpi__card kpi__card--blue">
            <div class="kpi__label">SEDANG DIPROSES</div>
            <div class="kpi__value"><?= esc($kpi['diproses'] ?? 0); ?></div>
            <div class="kpi__bar"></div>
        </div>

        <div class="kpi__card kpi__card--green">
            <div class="kpi__label">LAPORAN SELESAI</div>
            <div class="kpi__value"><?= esc($kpi['selesai'] ?? 0); ?></div>
            <div class="kpi__bar"></div>
        </div>
    </section>

    <!-- Panel Tabel -->
    <section class="panel">
        <div class="panel__body">

            <!-- Toolbar Filter -->
            <form class="table-tools" method="get" action="<?= current_url(); ?>">
                <div class="table-tools__left">
                    <div class="input">
                        <i class="bi bi-search"></i>
                        <input type="text" name="q" value="<?= esc($filters['q'] ?? ''); ?>"
                            placeholder="Cari ID, Pelapor, atau kata kunci pengaduan..." autocomplete="off">
                    </div>
                </div>

                <div class="table-tools__right">
                    <select class="select" name="status">
                        <option value="">Semua Status</option>
                        <option value="menunggu" <?= (($filters['status'] ?? '') === 'menunggu') ? 'selected' : ''; ?>>
                            Menunggu
                            Verifikasi</option>
                        <option value="diproses" <?= (($filters['status'] ?? '') === 'diproses') ? 'selected' : ''; ?>>
                            Sedang
                            Diproses</option>
                        <option value="selesai" <?= (($filters['status'] ?? '') === 'selesai') ? 'selected' : ''; ?>>
                            Selesai
                        </option>
                    </select>

                    <select class="select" name="kategori">
                        <option value="">Semua Kategori</option>
                        <?php foreach (($kategoriList ?? []) as $k): ?>
                            <option value="<?= esc($k['id']); ?>" <?= ((string) ($filters['kategori'] ?? '') === (string) $k['id']) ? 'selected' : ''; ?>>
                                <?= esc($k['nama']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>


                    <button class="btn btn--ghost" type="button" id="btnDate">
                        <i class="bi bi-calendar3"></i>
                        <span>Filter Tanggal</span>
                    </button>

                    <button class="btn btn--primary" type="submit">
                        <i class="bi bi-funnel"></i>
                        <span>Terapkan</span>
                    </button>
                </div>
            </form>

            <!-- (opsional) date range hidden -->
            <div class="date-row" id="dateRow" hidden>
                <div class="input">
                    <i class="bi bi-calendar3"></i>
                    <input type="date" name="start" form="__fake" value="<?= esc($filters['start'] ?? ''); ?>">
                </div>
                <div class="input">
                    <i class="bi bi-calendar3"></i>
                    <input type="date" name="end" form="__fake" value="<?= esc($filters['end'] ?? ''); ?>">
                </div>
                <small class="hint">Pilih rentang tanggal lalu klik Terapkan.</small>
            </div>

            <!-- Table -->
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 150px;">ID &amp; TANGGAL</th>
                            <th style="width: 200px;">IDENTITAS PELAPOR</th>
                            <th>KATEGORI &amp;<br>DESKRIPSI LAPORAN</th>
                            <th style="width: 150px;">STATUS<br>TERKINI</th>
                            <th style="width: 170px;">PETUGAS<br>TERKAIT</th>
                            <th style="width: 110px;">AKSI</th>
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
                                        <div class="cell-desc">
                                            <div class="badge badge--cat"><?= esc($r['kategori']); ?></div>
                                            <div class="cell-desc__text"><?= esc($r['deskripsi']); ?></div>
                                        </div>
                                    </td>

                                    <td>
                                        <?php
                                        $st = $r['status'];
                                        $cls = $st === 'menunggu' ? 'badge--warn' : ($st === 'diproses' ? 'badge--info' : 'badge--ok');
                                        $label = $st === 'menunggu' ? 'Menunggu' : ($st === 'diproses' ? 'Diproses' : 'Selesai');
                                        ?>
                                        <span class="badge <?= $cls; ?>"><?= $label; ?></span>
                                    </td>

                                    <td>
                                        <div class="cell-petugas">
                                            <?= esc($r['petugas'] ?? '-'); ?>
                                        </div>
                                    </td>

                                    <td>
                                        <a class="btn btn--sm btn--ghost" href="<?= base_url('admin/pengaduan/' . $r['id']); ?>">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="empty">
                                    <div class="empty__title">Belum ada data pengaduan</div>
                                    <div class="empty__subtitle">Gunakan filter di atas untuk menampilkan data.</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </section>

</div>

<script>
    // toggle date row (simple)
    (function () {
        const btn = document.getElementById('btnDate');
        const row = document.getElementById('dateRow');
        if (!btn || !row) return;
        btn.addEventListener('click', () => {
            row.hidden = !row.hidden;
        });
    })();
</script>
<?= $this->endSection(); ?>