<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Self Order - {{ $storeInfo['name'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg: #100E0C;
            --surface: #1C1713;
            --card: #251E19;
            --border: #3D3229;
            --primary: #D4A373;
            --primary-light: #FAEDCD;
            --text: #F4EAE0;
            --text-muted: #A89F91;
            --green: #10B981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            padding-bottom: 90px;
        }

        /* Mobile Header */
        .mobile-header {
            position: sticky;
            top: 0;
            background: rgba(28, 23, 19, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 50;
        }

        .table-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(212, 163, 115, 0.15);
            border: 1px solid var(--primary);
            color: var(--primary);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        /* Category Nav */
        .category-scroll {
            display: flex;
            gap: 8px;
            padding: 12px 18px;
            overflow-x: auto;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 61px;
            z-index: 40;
            scrollbar-width: none;
        }

        .category-scroll::-webkit-scrollbar {
            display: none;
        }

        .cat-chip {
            padding: 6px 14px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 20px;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            cursor: pointer;
        }

        .cat-chip.active {
            background: var(--primary);
            color: #1A120B;
            border-color: var(--primary);
        }

        /* Menu Grid */
        .menu-container {
            padding: 18px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 14px;
        }

        .menu-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 12px;
            display: flex;
            flex-direction: column;
        }

        .menu-img {
            height: 110px;
            background: #14100D;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: var(--primary);
            margin-bottom: 10px;
            overflow: hidden;
        }

        .menu-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .menu-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .menu-price {
            font-size: 14px;
            font-weight: 800;
            color: var(--primary-light);
            margin-top: auto;
            padding-top: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn-add {
            width: 28px;
            height: 28px;
            background: var(--primary);
            border: none;
            border-radius: 8px;
            color: #1A120B;
            font-size: 14px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* Bottom Floating Bar */
        .bottom-cart-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--surface);
            border-top: 1px solid var(--border);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 50;
            box-shadow: 0 -8px 25px rgba(0, 0, 0, 0.5);
        }

        .cart-info h4 {
            font-size: 12px;
            color: var(--text-muted);
        }

        .cart-info p {
            font-size: 17px;
            font-weight: 800;
            color: var(--primary);
        }

        .btn-checkout {
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 12px;
            color: #1A120B;
            font-size: 14px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        /* Checkout Modal */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.8);
            z-index: 100;
            align-items: flex-end;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-sheet {
            background: var(--surface);
            border-top: 1px solid var(--border);
            border-radius: 24px 24px 0 0;
            width: 100%;
            max-width: 500px;
            padding: 24px;
            max-height: 85vh;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <header class="mobile-header">
        <div>
            <h1 style="font-size: 15px; font-weight: 800; color: var(--primary-light);">{{ $storeInfo['name'] }}</h1>
            <span style="font-size: 11px; color: var(--text-muted);">Self-Ordering Mandiri</span>
        </div>
        <div class="table-indicator" id="tableIndicator">
            <i class="fa-solid fa-chair"></i>
            <span>{{ $table ? 'Meja ' . $table->number : 'Pilih Meja' }}</span>
        </div>
    </header>

    <div class="category-scroll">
        <button class="cat-chip active" onclick="filterCategory('all', this)">Semua</button>
        @foreach($categories as $cat)
            <button class="cat-chip" onclick="filterCategory('{{ $cat->id }}', this)">{{ $cat->name }}</button>
        @endforeach
    </div>

    <main class="menu-container">
        @foreach($categories as $cat)
            @foreach($cat->products as $p)
                <div class="menu-card" data-category="{{ $p->category_id }}">
                    <div class="menu-img">
                        @if($p->image)
                            <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}">
                        @else
                            <i class="fa-solid fa-mug-hot"></i>
                        @endif
                    </div>
                    <div class="menu-name">{{ $p->name }}</div>
                    <div class="menu-price">
                        <span>Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                        <button class="btn-add" onclick="addItem({{ json_encode($p) }})">+</button>
                    </div>
                </div>
            @endforeach
        @endforeach
    </main>

    <!-- Bottom Bar -->
    <div class="bottom-cart-bar">
        <div class="cart-info">
            <h4 id="itemCountText">0 Menu Dipilih</h4>
            <p id="totalPriceText">Rp 0</p>
        </div>
        <button class="btn-checkout" onclick="openOrderModal()">
            <span>Pesan Sekarang</span>
            <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>

    <!-- Order Sheet Modal -->
    <div class="modal" id="orderModal">
        <div class="modal-sheet">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 16px; font-weight: 800; color: var(--primary-light);">Konfirmasi Pesanan</h3>
                <button type="button" onclick="closeOrderModal()" style="background: none; border: none; color: var(--text-muted); font-size: 20px;">&times;</button>
            </div>

            <!-- Items -->
            <div id="cartItemsDetail" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;"></div>

            <!-- Form -->
            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px;">
                <div>
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Pilih Nomor Meja</label>
                    <select id="selfTableSelect" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border); border-radius: 8px; color: var(--text);">
                        @foreach($tables as $tbl)
                            <option value="{{ $tbl->id }}" {{ ($table && $table->id == $tbl->id) ? 'selected' : '' }}>
                                {{ $tbl->name ?? 'Meja ' . $tbl->number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Nama Anda</label>
                    <input type="text" id="selfCustName" placeholder="contoh: Budi" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border); border-radius: 8px; color: var(--text);">
                </div>

                <div>
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Metode Pembayaran</label>
                    <select id="selfPayMethod" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border); border-radius: 8px; color: var(--text);">
                        <option value="cash">Bayar Tunai di Kasir</option>
                        <option value="qris">QRIS (Scan & Bayar)</option>
                    </select>
                </div>
            </div>

            <button class="btn-checkout" style="width: 100%; justify-content: center;" onclick="submitSelfOrder()">
                Kirim Pesanan ke Dapur
            </button>
        </div>
    </div>

    <script>
        let selfCart = [];

        function filterCategory(catId, elem) {
            document.querySelectorAll('.cat-chip').forEach(c => c.classList.remove('active'));
            elem.classList.add('active');

            document.querySelectorAll('.menu-card').forEach(card => {
                if (catId === 'all' || card.dataset.category === catId) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function addItem(product) {
            const exist = selfCart.find(i => i.product.id === product.id);
            if (exist) {
                exist.quantity += 1;
            } else {
                selfCart.push({ product: product, quantity: 1 });
            }
            updateBottomBar();
        }

        function updateBottomBar() {
            let totalQty = 0;
            let totalPrice = 0;
            selfCart.forEach(i => {
                totalQty += i.quantity;
                totalPrice += i.quantity * parseFloat(i.product.price);
            });

            document.getElementById('itemCountText').innerText = totalQty + ' Menu Dipilih';
            document.getElementById('totalPriceText').innerText = 'Rp ' + totalPrice.toLocaleString('id-ID');
        }

        function openOrderModal() {
            if (selfCart.length === 0) {
                alert('Pilih minimal 1 menu terlebih dahulu!');
                return;
            }

            const container = document.getElementById('cartItemsDetail');
            container.innerHTML = '';
            selfCart.forEach(i => {
                const row = document.createElement('div');
                row.style.display = 'flex';
                row.style.justifyContent = 'space-between';
                row.style.fontSize = '13px';
                row.innerHTML = `
                    <span>${i.product.name} x${i.quantity}</span>
                    <strong style="color: var(--primary);">Rp ${(i.quantity * i.product.price).toLocaleString('id-ID')}</strong>
                `;
                container.appendChild(row);
            });

            document.getElementById('orderModal').classList.add('active');
        }

        function closeOrderModal() {
            document.getElementById('orderModal').classList.remove('active');
        }

        async function submitSelfOrder() {
            const tableId = document.getElementById('selfTableSelect').value;
            const custName = document.getElementById('selfCustName').value || 'Pelanggan QR';
            const payMethod = document.getElementById('selfPayMethod').value;

            const payload = {
                type: 'dine_in',
                table_id: tableId,
                customer_name: custName,
                payment_method: payMethod,
                items: selfCart.map(i => ({
                    product_id: i.product.id,
                    quantity: i.quantity
                }))
            };

            try {
                const res = await fetch('/api/orders/guest', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();
                if (json.success) {
                    alert('Pesanan #' + json.data.order_number + ' berhasil dikirim ke dapur! Silakan duduk santai.');
                    selfCart = [];
                    updateBottomBar();
                    closeOrderModal();
                } else {
                    alert('Gagal: ' + json.message);
                }
            } catch (e) {
                alert('Terjadi kendala jaringan.');
            }
        }
    </script>
</body>
</html>
