<?= $this->extend('admin/layouts/admin'); ?>
<?= $this->section('content'); ?>

<div class="page">
  <div class="page__header">
    <div>
      <h1 class="page__title">Manajemen User</h1>
      <p class="page__subtitle">Kelola akun pengguna & admin.</p>
    </div>

    <a class="btn btn--primary" href="<?= site_url('admin/users/create'); ?>">
      <i class="bi bi-plus-lg"></i> Tambah User
    </a>
  </div>

  <!-- KPI -->
  <section class="kpi">
    <div class="kpi__card kpi__card--red">
      <div class="kpi__label">TOTAL USER</div>
      <div class="kpi__value"><?= esc($stats['total'] ?? 0); ?></div>
      <div class="kpi__bar"></div>
    </div>
    <div class="kpi__card kpi__card--green">
      <div class="kpi__label">AKTIF</div>
      <div class="kpi__value"><?= esc($stats['aktif'] ?? 0); ?></div>
      <div class="kpi__bar"></div>
    </div>
    <div class="kpi__card kpi__card--orange">
      <div class="kpi__label">NONAKTIF</div>
      <div class="kpi__value"><?= esc($stats['nonaktif'] ?? 0); ?></div>
      <div class="kpi__bar"></div>
    </div>
    <div class="kpi__card kpi__card--blue">
      <div class="kpi__label">ADMIN / USER</div>
      <div class="kpi__value"><?= esc($stats['admin'] ?? 0); ?> / <?= esc($stats['user'] ?? 0); ?></div>
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
                   placeholder="Cari nama, email, no hp..." autocomplete="off">
          </div>
        </div>

        <div class="table-tools__right">
          <!-- ✅ role user di DB = masyarakat -->
          <select class="select" name="role">
            <option value="">Semua Role</option>
            <option value="admin" <?= (($filters['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="masyarakat" <?= (($filters['role'] ?? '') === 'masyarakat') ? 'selected' : ''; ?>>User</option>
          </select>

          <select class="select" name="status_akun">
            <option value="">Semua Status</option>
            <option value="aktif" <?= (($filters['status_akun'] ?? '') === 'aktif') ? 'selected' : ''; ?>>Aktif</option>
            <option value="nonaktif" <?= (($filters['status_akun'] ?? '') === 'nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
          </select>

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
              <th>NAMA</th>
              <th style="width:230px;">EMAIL</th>
              <th style="width:160px;">NO. HP</th>
              <th style="width:110px; text-align:center;">ROLE</th>
              <th style="width:120px; text-align:center;">STATUS</th>
              <th style="width:200px;">AKSI</th>
            </tr>
          </thead>

          <tbody>
          <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $r): ?>
              <?php
                $roleRaw = strtolower(trim($r['role'] ?? 'masyarakat'));
                $roleLabel = ($roleRaw === 'admin') ? 'ADMIN' : 'USER';
                $roleCls   = ($roleRaw === 'admin') ? 'badge--info' : 'badge--cat';

                $aktif = (($r['status_akun'] ?? '') === 'aktif');
              ?>
              <tr>
                <td style="text-align:center;"><?= esc($r['id']); ?></td>

                <td>
                  <div style="font-weight:900;"><?= esc($r['nama']); ?></div>
                  <div style="font-size:12px;color:var(--muted); margin-top:4px;">
                    <?= esc($r['created_at'] ?? ''); ?>
                  </div>
                </td>

                <td><?= esc($r['email']); ?></td>
                <td><?= esc($r['no_hp'] ?? '-'); ?></td>

                <td style="text-align:center;">
                  <span class="badge <?= $roleCls; ?>"><?= esc($roleLabel); ?></span>
                </td>

                <td style="text-align:center;">
                  <span class="badge <?= $aktif ? 'badge--ok' : 'badge--warn'; ?>">
                    <?= $aktif ? 'Aktif' : 'Nonaktif'; ?>
                  </span>
                </td>

                <td>
                  <a class="btn btn--sm btn--ghost" href="<?= site_url('admin/users/' . $r['id'] . '/edit'); ?>">
                    <i class="bi bi-pencil"></i> Edit
                  </a>

                  <form action="<?= site_url('admin/users/' . $r['id'] . '/toggle'); ?>" method="post" style="display:inline;">
                    <?= csrf_field(); ?>
                    <button class="btn btn--sm btn--ghost" type="submit"
                            onclick="return confirm('Ubah status user ini?')">
                      <i class="bi bi-power"></i>
                      <?= $aktif ? 'Nonaktifkan' : 'Aktifkan'; ?>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="empty">
                <div class="empty__title">Belum ada data user</div>
                <div class="empty__subtitle">Gunakan filter untuk menampilkan data.</div>
              </td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination: hanya muncul jika page > 1 -->
      <?php if ($pager && $pager->getPageCount('users_admin') > 1): ?>
        <div style="margin-top:14px;">
          <?= $pager->links('users_admin', 'default_full'); ?>
        </div>
      <?php endif; ?>

    </div>
  </section>
</div>

<?= $this->endSection(); ?>
