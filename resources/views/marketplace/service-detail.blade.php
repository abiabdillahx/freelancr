@extends('layouts.marketplace')

@section('title', 'Detail Jasa')

@section('navbar')
    @include('partials.marketplace-nav')
@endsection

@section('content')
    <section class="px-4 pt-10 sm:px-6 lg:px-8">
        <div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[1.3fr_0.7fr]">
            <div class="page-panel page-border rounded-[32px] border p-6 sm:p-8">
                <p id="service-category" class="text-sm font-semibold uppercase tracking-[0.2em] text-[#F7931E]">Memuat kategori</p>
                <h1 id="service-title" class="mt-3 text-4xl font-semibold leading-tight text-[#F05A28] sm:text-5xl">
                    Memuat detail jasa...
                </h1>
                <p id="service-summary" class="page-muted mt-4 text-base leading-7 sm:text-lg">
                    Tunggu sebentar, detail jasa sedang diambil dari API.
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-4 text-sm">
                    <span id="service-rating" class="page-chip rounded-full px-4 py-2 text-xs font-semibold">⭐ —</span>
                    <span id="service-reviews" class="page-chip rounded-full px-4 py-2 text-xs font-semibold">Belum ada review</span>
                </div>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <div class="page-surface page-border rounded-2xl border p-4">
                        <p class="page-muted text-xs font-semibold uppercase tracking-[0.2em]">Freelancer</p>
                        <p id="service-seller" class="mt-2 text-lg font-semibold text-[#F05A28]">-</p>
                        <p class="page-muted text-sm">Freelancer terverifikasi</p>
                    </div>
                    <div class="page-surface page-border rounded-2xl border p-4">
                        <div class="flex items-center justify-between">
                            <p class="page-muted text-xs font-semibold uppercase tracking-[0.2em]">Harga mulai</p>
                            <select id="currency-selector" class="rounded-lg border border-[#2A2A2A] bg-[#111111] px-2 py-1 text-[10px] font-bold text-[#F7931E] outline-none">
                                <option value="IDR">IDR</option>
                                <option value="USD">USD</option>
                                <option value="SGD">SGD</option>
                            </select>
                        </div>
                        <p id="service-price" class="mt-2 text-lg font-semibold text-[#F05A28]">Rp0</p>
                        <p class="page-muted text-sm">Harga bisa disesuaikan setelah diskusi</p>
                    </div>
                </div>

                <div class="mt-10">
                    <h2 class="text-xl font-semibold text-[#F05A28]">Ulasan Pembeli</h2>
                    <div id="service-reviews-list" class="mt-6 space-y-4">
                        <p class="page-muted text-sm italic">Memuat ulasan...</p>
                    </div>
                </div>
            </div>

            <aside class="space-y-4">
                <div class="page-card page-border page-shadow rounded-[28px] border p-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#F7931E]">Pilih paket</p>
                    <div class="mt-4 space-y-4">
                        <div class="page-surface page-border rounded-2xl border p-4">
                            <div class="flex items-center justify-between">
                                <p class="text-base font-semibold text-[#F05A28]">Basic</p>
                                <span id="package-basic" class="page-chip rounded-full px-3 py-1 text-xs font-semibold">Rp0</span>
                            </div>
                            <p class="page-muted mt-1 text-xs">Pengerjaan 3-4 hari</p>
                            <ul class="mt-3 space-y-2 text-xs">
                                <li class="page-muted flex items-center gap-2">
                                    <i data-lucide="dot" class="h-4 w-4 text-[#F7931E]"></i>
                                    1 konsep utama
                                </li>
                                <li class="page-muted flex items-center gap-2">
                                    <i data-lucide="dot" class="h-4 w-4 text-[#F7931E]"></i>
                                    2x revisi
                                </li>
                                <li class="page-muted flex items-center gap-2">
                                    <i data-lucide="dot" class="h-4 w-4 text-[#F7931E]"></i>
                                    File final siap pakai
                                </li>
                            </ul>
                        </div>
                        <div class="page-surface page-border rounded-2xl border p-4">
                            <div class="flex items-center justify-between">
                                <p class="text-base font-semibold text-[#F05A28]">Plus</p>
                                <span id="package-plus" class="page-chip rounded-full px-3 py-1 text-xs font-semibold">Rp0</span>
                            </div>
                            <p class="page-muted mt-1 text-xs">Pengerjaan 4-5 hari</p>
                            <ul class="mt-3 space-y-2 text-xs">
                                <li class="page-muted flex items-center gap-2">
                                    <i data-lucide="dot" class="h-4 w-4 text-[#F7931E]"></i>
                                    2 konsep + revisi lebih banyak
                                </li>
                                <li class="page-muted flex items-center gap-2">
                                    <i data-lucide="dot" class="h-4 w-4 text-[#F7931E]"></i>
                                    File editable jika dibutuhkan
                                </li>
                                <li class="page-muted flex items-center gap-2">
                                    <i data-lucide="dot" class="h-4 w-4 text-[#F7931E]"></i>
                                    Support chat prioritas
                                </li>
                            </ul>
                        </div>
                    </div>
                    <button id="order-btn" class="focus-ring mt-5 inline-flex h-12 w-full items-center justify-center rounded-2xl bg-[#F05A28] px-4 text-sm font-semibold text-white transition hover:bg-[#F7931E]">
                        Pesan jasa ini
                    </button>
                    <div id="order-message" class="hidden mt-3 text-center text-sm font-medium"></div>
                    <button class="page-outline focus-ring mt-3 inline-flex h-12 w-full items-center justify-center rounded-2xl border px-4 text-sm font-semibold">
                        Chat freelancer
                    </button>
                </div>
                <div class="page-surface page-border rounded-2xl border p-5">
                    <p class="page-muted text-xs font-semibold uppercase tracking-[0.2em]">Slot tersedia</p>
                    <p class="mt-2 text-lg font-semibold text-[#F05A28]">3 slot minggu ini</p>
                    <p class="page-muted mt-2 text-sm">Booking cepat biar dapet jadwal awal.</p>
                </div>
            </aside>
        </div>
    </section>

    <script>
        const serviceId = window.location.pathname.split('/').filter(Boolean).pop();
        const formatCurrency = (value) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value || 0);

        const setText = (id, value) => {
            const el = document.getElementById(id);
            if (el) {
                el.textContent = value;
            }
        };

        const loadService = async () => {
            try {
                const response = await fetch(`/api/services/${serviceId}`, { headers: { 'Accept': 'application/json' } });
                const payload = await response.json();
                const service = payload.data;
                if (!service) {
                    throw new Error('Service not found');
                }

                setText('service-category', service.category?.name ?? 'Jasa');
                setText('service-title', service.title ?? 'Jasa Freelancr');
                setText('service-summary', service.description ?? 'Detail jasa tidak tersedia.');
                setText('service-seller', service.user?.name ?? 'Freelancer');
                setText('service-price', formatCurrency(service.price));

                const ratingValue = service.average_rating ? Number(service.average_rating).toFixed(1) : '—';
                const reviewText = service.reviews_count ? `${service.reviews_count} review` : 'Belum ada review';
                setText('service-rating', `⭐ ${ratingValue}`);
                setText('service-reviews', reviewText);

                const basePrice = Number(service.price || 0);
                setText('package-basic', formatCurrency(basePrice));
                setText('package-plus', formatCurrency(basePrice ? basePrice * 1.6 : 0));
            } catch (error) {
                console.error('Gagal memuat detail jasa:', error);
                setText('service-title', 'Jasa tidak ditemukan');
                setText('service-summary', 'Silakan kembali ke daftar jasa dan pilih layanan lain.');
            }
        };

        const orderBtn = document.getElementById('order-btn');
        const orderMsg = document.getElementById('order-message');

        if (orderBtn) {
            orderBtn.addEventListener('click', async () => {
                const token = localStorage.getItem('freelancr_token');
                if (!token) {
                    window.location.href = '/login';
                    return;
                }

                orderBtn.disabled = true;
                orderBtn.textContent = 'Memproses...';
                orderMsg.classList.add('hidden');

                try {
                    const response = await fetch('/api/orders', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify({
                            service_id: serviceId,
                            note: 'Order dari detail jasa.'
                        })
                    });

                    const result = await response.json();

                    if (response.ok) {
                        orderMsg.textContent = 'Order berhasil dibuat! Mengalihkan...';
                        orderMsg.classList.remove('hidden', 'text-red-500');
                        orderMsg.classList.add('text-green-500');
                        setTimeout(() => window.location.href = '/pesanan', 1500);
                    } else {
                        orderMsg.textContent = result.message || 'Gagal membuat order.';
                        orderMsg.classList.remove('hidden', 'text-green-500');
                        orderMsg.classList.add('text-red-500');
                        orderBtn.disabled = false;
                        orderBtn.textContent = 'Pesan jasa ini';
                    }
                } catch (error) {
                    console.error('Order error:', error);
                    orderMsg.textContent = 'Terjadi kesalahan sistem.';
                    orderMsg.classList.remove('hidden');
                    orderMsg.classList.add('text-red-500');
                    orderBtn.disabled = false;
                    orderBtn.textContent = 'Pesan jasa ini';
                }
            });
        }

        const loadReviews = async () => {
            try {
                const response = await fetch(`/api/services/${serviceId}/reviews`, { headers: { 'Accept': 'application/json' } });
                const result = await response.json();
                const reviews = result.data || [];
                const container = document.getElementById('service-reviews-list');

                if (reviews.length === 0) {
                    container.innerHTML = '<p class="page-muted text-sm italic">Belum ada ulasan untuk jasa ini.</p>';
                    return;
                }

                container.innerHTML = reviews.map(r => `
                    <div class="page-surface page-border rounded-2xl border p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#F7931E]/10 text-[#F7931E]">
                                    <i data-lucide="user" class="h-4 w-4"></i>
                                </div>
                                <p class="text-sm font-semibold text-[#F5F5F4]">${r.order?.client?.name || 'User'}</p>
                            </div>
                            <span class="text-xs font-bold text-[#F7931E]">⭐ ${r.rating}.0</span>
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-[#E5E7EB]">"${r.comment || 'Tidak ada komentar.'}"</p>
                        <p class="mt-2 text-[10px] text-gray-500 uppercase tracking-widest">${new Date(r.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})}</p>
                    </div>
                `).join('');

                lucide.createIcons();
            } catch (error) {
                console.error('Error load reviews:', error);
            }
        };

        loadService();
        loadReviews();

        // Currency conversion logic
        document.getElementById('currency-selector').addEventListener('change', async (e) => {
            const targetCurrency = e.target.value;
            const priceEl = document.getElementById('service-price');
            const basicEl = document.getElementById('package-basic');
            const plusEl = document.getElementById('package-plus');
            
            // Get original price from current text (remove Rp, dots, etc)
            // But better to store it somewhere. Let's just re-fetch or use a global var if we had one.
            // For now, let's hit the conversion API
            
            // We need the numeric price. Let's fetch the service again or parse the existing IDR price.
            const idrText = document.getElementById('service-price').getAttribute('data-idr-price');
            if (!idrText) {
                // Store initial IDR price on first load
                const currentText = priceEl.textContent.replace(/[^0-9]/g, '');
                priceEl.setAttribute('data-idr-price', currentText);
            }
            
            const amount = priceEl.getAttribute('data-idr-price');
            
            if (targetCurrency === 'IDR') {
                priceEl.textContent = formatCurrency(amount);
                basicEl.textContent = formatCurrency(amount);
                plusEl.textContent = formatCurrency(amount * 1.6);
                return;
            }

            priceEl.textContent = '...';
            basicEl.textContent = '...';
            plusEl.textContent = '...';

            try {
                const res = await fetch(`/api/currency?amount=${amount}&symbols=${targetCurrency}`);
                const result = await res.json();
                const converted = result.data.rates[targetCurrency];
                
                const fmt = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: targetCurrency }).format(val);
                
                priceEl.textContent = fmt(converted);
                basicEl.textContent = fmt(converted);
                plusEl.textContent = fmt(converted * 1.6);
            } catch (err) {
                console.error(err);
                priceEl.textContent = 'Err';
            }
        });
    </script>
@endsection

@section('footer')
    @include('partials.marketplace-footer')
@endsection
