<?= $this->extend('user/layouts/main'); ?>
<?= $this->section('content'); ?>

<div style="max-width:1100px;margin:0 auto;padding:22px 16px;">

    <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div>
            <h1 style="margin:0;font-size:28px;font-weight:900;">Berita</h1>
            <p style="margin:6px 0 0;color:#6b7280;">Informasi terbaru seputar layanan.</p>
        </div>

        <form method="get" action="<?= current_url(); ?>" style="display:flex;gap:10px;align-items:center;">
            <input type="text" name="q" value="<?= esc($filters['q'] ?? '') ?>" placeholder="Cari berita..."
                style="height:42px;padding:0 14px;border:1px solid #e5e7eb;border-radius:12px;outline:none;min-width:260px;">
            <button type="submit"
                style="height:42px;padding:0 14px;border-radius:12px;border:0;font-weight:800;cursor:pointer;">
                Cari
            </button>
        </form>
    </div>

    <div style="height:14px;"></div>

    <?php if (!empty($rows)): ?>
        <div class="news-grid">
            <?php foreach ($rows as $r): ?>
                <article class="news-card">
                    <a href="<?= site_url('berita/' . $r['slug']); ?>" class="news-cover">
                        <?php
                        $img = !empty($r['gambar'])
                            ? base_url($r['gambar'])
                            : base_url('assets/img/placeholder.png');
                        ?>
                        <img src="<?= esc($img); ?>" alt="<?= esc($r['judul'] ?? 'Berita'); ?>"
                            onerror="this.src='<?= base_url('assets/img/placeholder.png'); ?>'">
                    </a>

                    <div class="news-body">
                        <div class="news-date"><?= esc($r['tanggal_publish'] ?? $r['created_at'] ?? ''); ?></div>
                        <h3 class="news-title"><?= esc($r['judul']); ?></h3>
                        <p class="news-excerpt"><?= esc($r['ringkas'] ?? ''); ?></p>
                        <a class="news-link" href="<?= site_url('berita/' . $r['slug']); ?>">Baca selengkapnya →</a>
                    </div>
                </article>
            <?php endforeach; ?>

        </div>

        <?php if ($pager && $pager->getPageCount('berita') > 1): ?>
            <div style="margin-top:16px;">
                <?= $pager->links('berita', 'default_full'); ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div style="margin-top:16px;background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:18px;">
            <div style="font-weight:900;">Belum ada berita</div>
            <div style="color:#6b7280;margin-top:4px;">Coba ubah kata kunci pencarian.</div>
        </div>
    <?php endif; ?>

</div>

<style>
    .news-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .news-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(16, 24, 40, .06);
    }

    .news-cover img {
        width: 100%;
        height: 170px;
        object-fit: cover;
        display: block;
    }

    .news-body {
        padding: 14px;
    }

    .news-date {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 6px;
    }

    .news-title {
        display: block;
        text-decoration: none;
        color: #111827;
        font-size: 16px;
        font-weight: 900;
        line-height: 1.25;
    }

    .news-desc {
        margin: 8px 0 0;
        color: #374151;
        font-size: 13px;
        line-height: 1.45;
    }

    .news-more {
        display: inline-block;
        margin-top: 10px;
        font-weight: 900;
        text-decoration: none;
    }

    @media (max-width: 980px) {
        .news-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .news-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?= $this->endSection(); ?>