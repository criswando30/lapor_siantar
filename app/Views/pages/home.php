<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<!-- ================= HERO + FORM ================= -->
<section class="relative overflow-hidden"
  style="background-image: url('<?= base_url('assets/img/hero.png') ?>'); background-size: calc(100% - 15px); background-position: center top; background-repeat: no-repeat;">
  
  <!-- overlay lembut -->
  <div class="absolute inset-0 bg-white/60"></div>

  <div class="relative container mx-auto px-5">

    <!-- Judul -->
    <div class="pt-10 md:pt-12 text-center">
      <h1 class="text-xl md:text-3xl font-extrabold text-gray-800 leading-tight max-w-2xl mx-auto">
        Layanan Pengaduan Masyarakat<br>Pematangsiantar
      </h1>
    </div>

    <!-- Spacer -->
    <div class="h-64 md:h-80 lg:h-[360px]"></div>

    <!-- FORM CARD -->
    <div class="mt-24 md:mt-40 pb-16 md:pb-20 flex justify-center">
      <div class="w-full max-w-2xl">
        <div class="bg-white shadow-md overflow-hidden border border-gray-300">

          <div class="bg-primary py-3">
            <h2 class="text-white font-bold uppercase text-sm tracking-wide text-center">
              SAMPAIKAN LAPORAN ANDA
            </h2>
          </div>

          <div class="p-6 space-y-4">
            <input class="w-full px-3 py-2 text-sm border border-gray-300" placeholder="Ketik Judul Laporan Anda *">
            <textarea class="w-full px-3 py-2 text-sm border border-gray-300" rows="5" placeholder="Ketik Isi Laporan *"></textarea>
            <input class="w-full px-3 py-2 text-sm border border-gray-300" placeholder="Pilih Tanggal Kejadian *"
              type="text" onfocus="this.type='date'" onblur="if(!this.value)this.type='text'">
            <input class="w-full px-3 py-2 text-sm border border-gray-300" placeholder="Ketik Lokasi Kejadian *">

            <select class="w-full px-3 py-2 text-sm border border-gray-300">
              <option disabled selected>Ketik Instansi Tujuan*</option>
              <option>Dinas Kesehatan</option>
              <option>Dinas Perhubungan</option>
              <option>Dinas Kebersihan</option>
            </select>

            <select class="w-full px-3 py-2 text-sm border border-gray-300">
              <option disabled selected>Pilih Kategori Laporan Anda *</option>
              <option>Keluhan</option>
              <option>Aspirasi</option>
              <option>Informasi</option>
            </select>

            <!-- Upload -->
            <input id="lampiran" type="file" accept="image/*" multiple class="hidden" onchange="handleLampiran(this)">

            <div id="uploadBox"
              class="hidden border-2 border-dashed border-gray-300 h-20 flex items-center justify-center cursor-pointer"
              onclick="document.getElementById('lampiran').click()">
              <p class="text-sm font-bold">UPLOAD LAMPIRAN (MAX 2MB)</p>
            </div>

            <div id="fileList" class="mt-3 space-y-2 text-sm"></div>

            <div class="flex items-center justify-between pt-2">
              <button type="button" onclick="toggleUploadBox()" class="flex items-center gap-2 text-sm text-gray-600">
                📎 Upload Lampiran
              </button>
              <button class="px-6 py-2 bg-primary text-white text-xs font-bold">LAPOR!</button>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= ALUR PENGADUAN ================= -->
<!-- Versi dengan Bootstrap Icons - BOLD -->
<section class="py-14 bg-white">
  <div class="container mx-auto px-5 text-center">
    <h2 class="text-3xl font-bold text-gray-900">Alur Pengaduan Masyarakat</h2>
    <p class="mt-3 text-sm text-gray-600 max-w-2xl mx-auto">
      Alur penanganan dan pengaduan masyarakat yang sistematis untuk memastikan<br>
      setiap laporan di tindak lanjuti oleh instansi terkait.
    </p>
    <div class="mt-16 relative max-w-6xl mx-auto px-8">
      <!-- Garis lebih tebal -->
      <div class="hidden md:block absolute top-[60px] left-[12%] right-[12%] h-[3px] bg-blue-500"></div>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        
        <!-- Step 1 -->
        <div class="flex flex-col items-center relative">
          <!-- Border lebih tebal: 4px -->
          <div class="w-[120px] h-[120px] rounded-full bg-gray-50 border-[4px] border-blue-500 flex items-center justify-center relative z-10">
            <!-- Icon lebih besar dan tebal -->
            <i class="bi bi-file-earmark-text text-blue-500" style="font-size: 3.75rem; font-weight: 700; stroke-width: 2;"></i>
          </div>
          <h3 class="mt-5 font-bold text-gray-900 text-base">Tulis Laporan</h3>
          <p class="mt-2 text-xs text-gray-500 leading-relaxed max-w-[200px]">
            Tulis keluhan Anda secara jelas dan sertakan bukti pendukung jika ada.
          </p>
        </div>
        
        <!-- Step 2 -->
        <div class="flex flex-col items-center relative">
          <div class="w-[120px] h-[120px] rounded-full bg-gray-50 border-[4px] border-blue-500 flex items-center justify-center relative z-10">
            <i class="bi bi-file-earmark-check text-blue-500" style="font-size: 3.75rem; font-weight: 700;"></i>
          </div>
          <h3 class="mt-5 font-bold text-gray-900 text-base">Verifikasi</h3>
          <p class="mt-2 text-xs text-gray-500 leading-relaxed max-w-[200px]">
            Tim kami akan memverifikasi data dan konten laporan anda dalam 1 x 24 jam.
          </p>
        </div>
        
        <!-- Step 3 -->
        <div class="flex flex-col items-center relative">
          <div class="w-[120px] h-[120px] rounded-full bg-gray-50 border-[4px] border-blue-500 flex items-center justify-center relative z-10">
            <i class="bi bi-person-gear text-blue-500" style="font-size: 3.75rem; font-weight: 700;"></i>
          </div>
          <h3 class="mt-5 font-bold text-gray-900 text-base">Tindak Lanjut</h3>
          <p class="mt-2 text-xs text-gray-500 leading-relaxed max-w-[200px]">
            Instansi terkait akan menindaklanjuti laporan sesuai kewenangannya.
          </p>
        </div>
        
        <!-- Step 4 -->
        <div class="flex flex-col items-center relative">
          <div class="w-[120px] h-[120px] rounded-full bg-gray-50 border-[4px] border-blue-500 flex items-center justify-center relative z-10">
            <i class="bi bi-check-circle-fill text-blue-500" style="font-size: 3.75rem; font-weight: 700;"></i>
          </div>
          <h3 class="mt-5 font-bold text-gray-900 text-base">Selesai</h3>
          <p class="mt-2 text-xs text-gray-500 leading-relaxed max-w-[200px]">
            Dapatkan notifikasi penyelesaian dan beri penilaian terhadap pelayanan.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
/* Tambahkan CSS untuk membuat icon Bootstrap Icons lebih tebal */
.bi-file-earmark-text,
.bi-file-earmark-check,
.bi-person-gear,
.bi-check-circle-fill {
  -webkit-text-stroke: 0.5px currentColor;
  font-weight: 900 !important;
}
</style>

<!-- ================= STATISTIK ================= -->
<section class="bg-primary py-14">
  <div class="container mx-auto px-5 text-center text-white">
    <h2 class="text-2xl font-extrabold">Statistik Pengaduan Masyarakat</h2>

    <div class="mt-10 flex justify-center gap-8">
      <div class="bg-white text-primary rounded-lg px-8 py-6">
        <div class="text-3xl font-black">0</div>
        <div class="text-xs font-bold mt-2">Diterima</div>
      </div>
      <div class="bg-white text-primary rounded-lg px-8 py-6">
        <div class="text-3xl font-black">0</div>
        <div class="text-xs font-bold mt-2">Diproses</div>
      </div>
      <div class="bg-white text-primary rounded-lg px-8 py-6">
        <div class="text-3xl font-black">0</div>
        <div class="text-xs font-bold mt-2">Selesai</div>
      </div>
    </div>
  </div>
</section>

<!-- ================= SCRIPT ================= -->
<script>
  let selectedFiles = [];

  function toggleUploadBox() {
    document.getElementById('uploadBox').classList.toggle('hidden');
  }

  function handleLampiran(input) {
    const maxSize = 2 * 1024 * 1024;

    Array.from(input.files).forEach(file => {
      if (file.size > maxSize) {
        alert('Ukuran file maksimal 2MB');
        return;
      }
      selectedFiles.push(file);
    });

    input.value = '';
    renderFileList();
  }

  function renderFileList() {
    const list = document.getElementById('fileList');
    list.innerHTML = '';
    selectedFiles.forEach((f, i) => {
      list.innerHTML += `
        <div class="flex justify-between">
          <span>${f.name}</span>
          <button onclick="removeFile(${i})" class="text-red-500 font-bold">&times;</button>
        </div>`;
    });
  }

  function removeFile(i) {
    selectedFiles.splice(i, 1);
    renderFileList();
  }
</script>
<?= $this->endSection() ?>
