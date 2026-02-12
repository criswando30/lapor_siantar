<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= esc($title ?? 'LaporSiantar') ?></title>

  <!-- (Opsional) masih CDN, belum 100% offline
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/> -->

  <!-- CSS manual lokal -->
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap"
  rel="stylesheet" />
</head>



<body class="auth-body">
  <?= $this->renderSection('content') ?>
</body>

</html>