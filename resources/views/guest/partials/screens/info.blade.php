<div id="info-screen" class="full-screen-page" style="display: none;">
    <div class="page-header">
        <button class="back-btn" type="button" onclick="history.back()">
            <i class="fas fa-chevron-left"></i>
        </button>
        <h2 class="page-title">PAS MOBILE</h2>
    </div>
    <div class="info-content scroll-content">
        {!! $about?->content ? $about->content : '<h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 15px;">Info</h3><p style="font-size: 0.8rem;">Konten belum tersedia.</p>' !!}
    </div>
</div>
