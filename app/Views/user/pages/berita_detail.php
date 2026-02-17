<?= $this->extend('user/layouts/main'); ?>
<?= $this->section('content'); ?>

<div style="max-width:900px;margin:0 auto;padding:22px 16px;">

    <a href="<?= site_url('berita'); ?>" style="text-decoration:none;font-weight:800;">← Kembali</a>

    <div style="height:10px;"></div>

    <h1 style="margin:0;font-size:28px;font-weight:950;line-height:1.15;">
        <?= esc($row['judul']); ?>
    </h1>

    <div style="margin-top:8px;color:#6b7280;font-size:13px;">
        <?= esc($row['published_at'] ?? $row['created_at'] ?? ''); ?>
    </div>

    <?php if (!empty($row['gambar'])): ?>
        <div style="margin-top:14px;">
            <img src="<?= base_url($row['gambar']); ?>" alt="<?= esc($row['judul']); ?>"
                style="width:100%;max-height:360px;object-fit:cover;border-radius:18px;border:1px solid #e5e7eb;">
        </div>
    <?php endif; ?>


    <article
        style="margin-top:14px;background:#fff;border:1px solid #e5e7eb;border-radius:18px;padding:18px;line-height:1.7;color:#111827;">
        <?= nl2br(esc($row['isi'] ?? '')); ?>
    </article>

</div>

<?= $this->endSection(); ?>