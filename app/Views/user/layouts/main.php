<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title><?= esc($title ?? 'LaporSiantar - Layanan Pengaduan Masyarakat') ?></title>

  <!-- CSS Manual Lokal -->
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
</head>

<body>
  <?= $this->include('user/partials/header') ?>
  <?= $this->renderSection('content') ?>
  <?= $this->include('user/partials/footer') ?>
  <?= $this->include('user/partials/login_modal') ?>
</body>
</html>