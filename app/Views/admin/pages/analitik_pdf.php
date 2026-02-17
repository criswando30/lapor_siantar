<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body{ font-family: DejaVu Sans, sans-serif; font-size:12px; }
    h2{ margin:0 0 6px; }
    .muted{ color:#666; font-size:11px; margin-bottom:10px; }
    table{ width:100%; border-collapse:collapse; margin-top:8px; }
    th, td{ border:1px solid #ddd; padding:6px; }
    th{ background:#f2f2f2; }
  </style>
</head>
<body>

<h2>Laporan Analitik</h2>
<div class="muted">
  Periode: <?= esc($filters['from'] ?? '-'); ?> s/d <?= esc($filters['to'] ?? '-'); ?>
</div>

<table>
  <tr>
    <th>Total</th><th>Diterima</th><th>Diverifikasi</th><th>Diproses</th><th>Selesai</th>
  </tr>
  <tr>
    <td><?= (int)($kpi['total'] ?? 0); ?></td>
    <td><?= (int)($kpi['laporan_diterima'] ?? 0); ?></td>
    <td><?= (int)($kpi['diverifikasi'] ?? 0); ?></td>
    <td><?= (int)($kpi['dalam_proses'] ?? 0); ?></td>
    <td><?= (int)($kpi['selesai'] ?? 0); ?></td>
  </tr>
</table>

<h3>Rekap Per Kategori</h3>
<table>
  <tr><th>Kategori</th><th>Total</th></tr>
  <?php foreach (($byKategori ?? []) as $r): ?>
    <tr>
      <td><?= esc($r['nama_kategori'] ?? '-'); ?></td>
      <td><?= (int)($r['total'] ?? 0); ?></td>
    </tr>
  <?php endforeach; ?>
</table>

<h3>Detail (maks 2000)</h3>
<table>
  <tr>
    <th>ID</th><th>Kode</th><th>Tanggal</th><th>Status</th><th>Kategori</th><th>Sub</th><th>Lokasi</th>
  </tr>
  <?php foreach (($details ?? []) as $d): ?>
    <tr>
      <td><?= esc($d['id']); ?></td>
      <td><?= esc($d['kode_tiket']); ?></td>
      <td><?= esc($d['created_at']); ?></td>
      <td><?= esc($d['status']); ?></td>
      <td><?= esc($d['nama_kategori'] ?? '-'); ?></td>
      <td><?= esc($d['nama_subkategori'] ?? '-'); ?></td>
      <td><?= esc($d['lokasi'] ?? '-'); ?></td>
    </tr>
  <?php endforeach; ?>
</table>

</body>
<?php if (!empty($isPrint)): ?>
<script>
  window.onload = () => window.print();
</script>
<?php endif; ?>


</html>
