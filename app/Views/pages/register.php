<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<section class="auth-page" style="background:#1d84e4;">
  <!-- Logo -->
  <div class="auth-logo">
    <img src="<?= base_url('assets/img/logo_white.png') ?>" alt="LaporSiantar" class="auth-logo__img" />
  </div>

  <!-- Card -->
  <div class="auth-card">
    <div class="auth-card__head">
      <h1 class="auth-title">Daftar</h1>
    </div>

    <div class="auth-card__body">
      <?php
      $error = session()->getFlashdata('register_error');
      $success = session()->getFlashdata('register_success');
      $validation = $validation ?? session('validation');
      $errors = session('errors');
      ?>

      <?php if (!empty($success)): ?>
        <div class="status-alert status-alert--success">
          <?= esc($success) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
        <div class="status-alert status-alert--danger">
          <?= esc($error) ?>
        </div>
      <?php endif; ?>

      <?php if ($validation && method_exists($validation, 'listErrors')): ?>
        <?= $validation->listErrors('list') ?>
      <?php endif; ?>

      <?php if (is_array($errors) && !empty($errors)): ?>
        <div class="status-alert status-alert--danger">
          <ul class="auth-error-list">
            <?php foreach ($errors as $e): ?>
              <li><?= esc($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <!-- Alert info -->
      <div class="auth-info">
        <div class="auth-info__row">
          <span class="auth-info__icon">⚠</span>
          <div>
            <div class="auth-info__title">Mengapa kami meminta data ini?</div>
            <p class="auth-info__text">
              Layanan SP4N-LAPOR! membutuhkan data pribadi pengguna sebagai jaminan keabsahan dan/atau aspirasi yang
              disampaikan,
              sebagai identifikasi publik, pengelolaan dan analisis data, penyusunan perencanaan dan pengambilan
              kebijakan,
              monitoring dan evaluasi, serta mendorong terciptanya kebijakan yang inklusif.
            </p>
          </div>
        </div>
      </div>

      <!-- FORM -->
      <form action="<?= site_url('register') ?>" method="post" class="auth-form">
        <?= csrf_field() ?>

        <div class="auth-grid">
          <div class="auth-field">
            <label class="auth-label" for="nik">NIK</label>
            <input id="nik" name="nik" value="<?= old('nik') ?>" class="auth-input" placeholder="NIK" />
          </div>

          <div class="auth-field">
            <label class="auth-label" for="nama_lengkap">Nama Lengkap*</label>
            <input id="nama_lengkap" name="nama_lengkap" value="<?= old('nama_lengkap') ?>" required class="auth-input"
              placeholder="Nama Lengkap" />
          </div>

          <div class="auth-field">
            <label class="auth-label" for="tempat_tinggal">Tempat Tinggal Saat Ini*</label>
            <input id="tempat_tinggal" name="tempat_tinggal" value="<?= old('tempat_tinggal') ?>" required
              class="auth-input" placeholder="Tempat Tinggal Saat Ini" />
          </div>

          <div class="auth-field">
            <label class="auth-label" for="tanggal_lahir">Tanggal Lahir*</label>
            <input id="tanggal_lahir" name="tanggal_lahir" type="date" value="<?= old('tanggal_lahir') ?>" required
              class="auth-input" />
          </div>

          <div class="auth-field">
            <label class="auth-label" for="jenis_kelamin">Jenis Kelamin*</label>
            <select id="jenis_kelamin" name="jenis_kelamin" required class="auth-input">
              <option value="" disabled <?= old('jenis_kelamin') ? '' : 'selected' ?>>Pilih</option>
              <option value="L" <?= old('jenis_kelamin') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
              <option value="P" <?= old('jenis_kelamin') === 'P' ? 'selected' : '' ?>>Perempuan</option>
            </select>
          </div>

          <div class="auth-field">
            <label class="auth-label" for="no_telp">No. Telp Aktif*</label>
            <input id="no_telp" name="no_telp" value="<?= old('no_telp') ?>" required class="auth-input"
              placeholder="No. Telp Aktif" />
          </div>

          <div class="auth-field">
            <label class="auth-label" for="email">Email*</label>
            <input id="email" name="email" type="email" value="<?= old('email') ?>" required class="auth-input"
              placeholder="Email" />
          </div>

          <div class="auth-field">
            <label class="auth-label" for="username">Username*</label>
            <input id="username" name="username" value="<?= old('username') ?>" required class="auth-input"
              placeholder="Username" />
          </div>

          <div class="auth-field">
            <label class="auth-label" for="password">Password*</label>
            <input id="password" name="password" type="password" required class="auth-input" placeholder="Password" />
          </div>

          <div class="auth-field">
            <label class="auth-label" for="password_confirm">Password Confirmation*</label>
            <input id="password_confirm" name="password_confirm" type="password" required class="auth-input"
              placeholder="Password Confirmation" />
          </div>

        </div>

        <div class="auth-actions">
          <button type="submit" class="btn btn--primary auth-submit">
            DAFTAR
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- tombol kembali -->
  <div class="auth-back">
    <a href="<?= site_url() ?>" class="btn auth-back__btn">
      KEMBALI KE HALAMAN DEPAN
    </a>
  </div>
</section>

<?= $this->endSection() ?>