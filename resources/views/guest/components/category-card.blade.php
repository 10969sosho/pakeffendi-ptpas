{{-- Category Card Component --}}
@props(['category'])

<div class="category-card" data-category-id="{{ $category['id'] ?? 1 }}">
    <div class="category-icon">
        <i class="bi bi-{{ $category['icon'] ?? 'box' }}"></i>
    </div>
    <div class="category-name">{{ $category['name'] ?? 'Kategori' }}</div>
</div>