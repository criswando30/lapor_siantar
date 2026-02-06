<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= esc($title ?? 'LaporSiantar') ?></title>

  <!-- Tailwind & asset lain -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: "#1d84e4",
            secondary: "#0056b3",
          },
          fontFamily: {
            display: ["Plus Jakarta Sans", "sans-serif"],
          },
        },
      },
    };
  </script>

  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
  </style>
</head>

<body class="bg-primary">
  <?= $this->renderSection('content') ?>
</body>
</html>
