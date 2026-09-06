@extends('layouts.app')

@section('title', 'Manajemen Menu & Produk')
@section('page_title', 'Katalog Menu & Produk Warkop')

@section('styles')
<style>
    .catalog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .search-input {
        padding: 10px 16px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        color: var(--text-main);
        font-size: 14px;
        width: 260px;
        outline: none;
    }

    .select-category {
        padding: 10px 16px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        color: var(--text-main);
        font-size: 14px;
        outline: none;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .product-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.2s ease;
    }

    .product-card:hover {
        transform: translateY(-2px);
        border-color: var(--primary);
    }

    .product-thumb {
        height: 150px;
        background: linear-gradient(135deg, #2A241F, #1C1713);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        color: var(--primary);
        position: relative;
    }

    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .fav-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(212, 163, 115, 0.9);
        color: #1A120B;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }

    .product-content {
        padding: 18px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .product-category {
        font-size: 11px;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
    }

    .product-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-main);
        margin: 4px 0 8px;
    }

    .product-desc {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.4;
        margin-bottom: 12px;
        flex: 1;
    }

    .product-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 14px;
        border-top: 1px solid rgba(56, 48, 42, 0.5);
    }

    .product-price {
        font-size: 16px;
        font-weight: 800;
        color: var(--primary-light);
    }

    .actions {
        display: flex;
        gap: 6px;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 100;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        width: 100%;
        max-width: 540px;
        padding: 28px;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 12px 14px;
        background: #12100E;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        color: var(--text-main);
        font-size: 14px;
        outline: none;
    }
</style>
@endsection

@section('content')
<div class="catalog-header">
    <form action="{{ route('admin.products.index') }}" method="GET" class="filter-group">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama menu..." class="search-input">
        <select name="category_id" class="select-category" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-secondary">
            <i class="fa-solid fa-magnifying-glass"></i> Filter
        </button>
    </form>

    <button type="button" class="btn btn-primary" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i> Tambah Menu Baru
    </button>
</div>

<!-- Product Cards Grid -->
<div class="product-grid">
    @forelse($products as $prod)
        <div class="product-card">
            <div class="product-thumb">
                @if($prod->image)
                    <img src="{{ asset('storage/' . $prod->image) }}" alt="{{ $prod->name }}">
                @else
                    <i class="fa-solid fa-mug-hot"></i>
                @endif

                @if($prod->is_favorite)
                    <span class="fav-badge"><i class="fa-solid fa-star"></i> Favorit</span>
                @endif
            </div>
            <div class="product-content">
                <span class="product-category">{{ $prod->category->name ?? 'Menu' }}</span>
                <h3 class="product-title">{{ $prod->name }}</h3>
                <p class="product-desc">{{ Str::limit($prod->description, 70) }}</p>
                <div class="product-footer">
                    <div class="product-price">Rp {{ number_format($prod->price, 0, ',', '.') }}</div>
                    <div class="actions">
                        <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary" style="padding: 6px 10px; color: var(--accent-red);" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">
            <i class="fa-solid fa-utensils" style="font-size: 36px; margin-bottom: 12px; display: block;"></i>
            Belum ada produk yang cocok dengan pencarian.
        </div>
    @endforelse
</div>

<div style="margin-top: 24px;">
    {{ $products->links() }}
</div>

<!-- Modal Add Product -->
<div class="modal" id="addModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main);">Tambah Menu Baru</h3>
            <button type="button" onclick="closeAddModal()" style="background: none; border: none; color: var(--text-muted); font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Kategori Menu</label>
                <select name="category_id" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Nama Menu</label>
                <input type="text" name="name" placeholder="contoh: Kopi Sanger Tarik" required>
            </div>

            <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label>Harga Jual (Rp)</label>
                    <input type="number" name="price" placeholder="10000" required>
                </div>
                <div>
                    <label>Harga Pokok / HPP (Rp)</label>
                    <input type="number" name="cost_price" placeholder="4000">
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi Menu</label>
                <textarea name="description" rows="3" placeholder="Jelaskan cita rasa dan racikan..."></textarea>
            </div>

            <div class="form-group">
                <label>Foto Produk (Opsional)</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="is_favorite" id="is_favorite" value="1" style="width: auto;">
                <label for="is_favorite" style="margin-bottom: 0;">Tandai sebagai Menu Favorit / Best Seller</label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Menu</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').classList.add('active');
    }
    function closeAddModal() {
        document.getElementById('addModal').classList.remove('active');
    }
</script>
@endsection
