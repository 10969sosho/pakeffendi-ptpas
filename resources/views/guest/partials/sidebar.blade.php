<div id="sidebar-menu" class="sidebar">
    <div class="sidebar-header">
        <button id="close-sidebar" class="sidebar-back-btn">
            <i class="fas fa-chevron-left"></i>
        </button>
        <h2 class="sidebar-title">Kategori</h2>
    </div>
    <div class="sidebar-content">
        <ul class="category-list">
            @foreach($categories as $category)
                <li role="button" tabindex="0" data-category-id="{{ $category->category_code }}">
                    <i class="fas fa-box-open icon-grey"></i>
                    <span>{{ $category->name }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
