<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>


<section class="min-h-screen flex flex-col items-center justify-start px-4 py-10" style="background:#1d84e4;">
  <!-- Logo -->
  <div class="w-full flex justify-center mb-8">
    <img
      src="<?= base_url('assets/img/logo_white.png') ?>"
      alt="LaporSiantar"
      class="h-10 md:h-12 object-contain"
    />
  </div>

  <!-- Card -->
  <div class="w-full max-w-3xl bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
    <div class="px-6 pt-8 pb-4 text-center">
      <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800">Daftar</h1>
    </div>

    <div class="px-6 pb-8">
      <?php
        $error = session()->getFlashdata('register_error');
        $success = session()->getFlashdata('register_success');
        $validation = $validation ?? session('validation');
        $errors = session('errors'); // optional: array errors
      ?>

      <?php if (!empty($success)): ?>
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
          <?= esc($success) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
          <?= esc($error) ?>
        </div>
      <?php endif; ?>

      <?php if ($validation && method_exists($validation, 'listErrors')): ?>
        <?= $validation->listErrors('list') ?>
      <?php endif; ?>

      <?php if (is_array($errors) && !empty($errors)): ?>
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
          <ul class="list-disc pl-5 space-y-1">
            <?php foreach ($errors as $e): ?>
              <li><?= esc($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <!-- Alert info (seperti gambar) -->
      <div class="mb-6 rounded-md bg-red-500 text-white p-4 text-xs leading-relaxed">
        <div class="flex gap-2">
          <span class="font-bold">⚠</span>
          <div>
            <div class="font-extrabold mb-1">Mengapa kami meminta data ini?</div>
            <p class="opacity-95">
              Layanan SP4N-LAPOR! membutuhkan data pribadi pengguna sebagai jaminan keabsahan dan/atau aspirasi yang disampaikan,
              sebagai identifikasi publik, pengelolaan dan analisis data, penyusunan perencanaan dan pengambilan kebijakan,
              monitoring dan evaluasi, serta mendorong terciptanya kebijakan yang inklusif.
            </p>
          </div>
        </div>
      </div>

      <!-- FORM -->
      <form action="<?= site_url('register') ?>" method="post" class="space-y-6">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- KIRI -->
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1" for="nik">NIK</label>
            <input id="nik" name="nik" value="<?= old('nik') ?>"
              class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="NIK" />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1" for="nama_lengkap">Nama Lengkap*</label>
            <input id="nama_lengkap" name="nama_lengkap" value="<?= old('nama_lengkap') ?>" required
              class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="Nama Lengkap" />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1" for="tempat_tinggal">Tempat Tinggal Saat Ini*</label>
            <input id="tempat_tinggal" name="tempat_tinggal" value="<?= old('tempat_tinggal') ?>" required
              class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="Tempat Tinggal Saat Ini" />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1" for="tanggal_lahir">Tanggal Lahir*</label>
            <input id="tanggal_lahir" name="tanggal_lahir" type="date" value="<?= old('tanggal_lahir') ?>" required
              class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200" />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1" for="jenis_kelamin">Jenis Kelamin*</label>
            <select id="jenis_kelamin" name="jenis_kelamin" required
              class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200">
              <option value="" disabled <?= old('jenis_kelamin') ? '' : 'selected' ?>>Pilih</option>
              <option value="L" <?= old('jenis_kelamin') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
              <option value="P" <?= old('jenis_kelamin') === 'P' ? 'selected' : '' ?>>Perempuan</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1" for="no_telp">No. Telp Aktif*</label>
            <input id="no_telp" name="no_telp" value="<?= old('no_telp') ?>" required
              class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="No. Telp Aktif" />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1" for="pekerjaan">Pekerjaan*</label>
            <input id="pekerjaan" name="pekerjaan" value="<?= old('pekerjaan') ?>" required
              class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="Pekerjaan" />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1" for="email">Email*</label>
            <input id="email" name="email" type="email" value="<?= old('email') ?>" required
              class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="Email" />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1" for="username">Username*</label>
            <input id="username" name="username" value="<?= old('username') ?>" required
              class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="Username" />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1" for="password">Password*</label>
            <input id="password" name="password" type="password" required
              class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="Password" />
          </div>

          <div class="md:col-span-2 md:max-w-sm">
            <label class="block text-xs font-semibold text-gray-700 mb-1" for="password_confirm">Password Confirmation*</label>
            <input id="password_confirm" name="password_confirm" type="password" required
              class="w-full border border-gray-300 px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="Password Confirmation" />
          </div>
        </div>

        <div class="pt-2">
          <button type="submit"
            class="w-full bg-primary hover:bg-secondary text-white font-extrabold tracking-wider py-3 rounded-md transition">
            DAFTAR
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- tombol kembali -->
  <div class="w-full max-w-3xl mt-6 flex justify-center">
    <a href="<?= site_url() ?>"
      class="inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-extrabold tracking-wide px-8 py-3 rounded-md transition">
      KEMBALI KE HALAMAN DEPAN
    </a>
  </div>
</section>

<?= $this->endSection() ?>
