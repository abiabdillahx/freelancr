@extends('layouts.marketplace')

@section('title', 'Jasa')

@section('navbar')
    @include('partials.marketplace-nav')
@endsection

@section('content')
    <section class="px-4 pt-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="page-panel page-border rounded-[32px] border p-6 sm:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="mb-3 text-sm font-semibold uppercase tracking-[0.2em] text-[#F7931E]">Jelajahi jasa</p>
                        <h1 class="text-4xl font-semibold leading-tight text-[#F05A28] sm:text-5xl">
                            Cari jasa yang siap bantu tugas dan proyek kampus.
                        </h1>
                        <p class="page-muted mt-4 text-base leading-7 sm:text-lg">
                            Pilih freelancer berdasarkan kategori, rating, dan harga yang sesuai kebutuhan.
                        </p>
                    </div>
                    <div class="page-surface page-border field-shell flex h-12 w-full max-w-md items-center gap-3 rounded-2xl border px-4">
                        <i data-lucide="search" class="h-5 w-5 text-[#F7931E]"></i>
                        <input id="service-search" type="text" placeholder="Coba: desain poster event" class="h-full flex-1 bg-transparent text-sm font-semibold">
                    </div>
                </div>

                <div id="category-filters" class="mt-6 flex flex-wrap gap-3">
                    <button type="button" data-category="" class="page-chip rounded-full px-4 py-2 text-xs font-semibold">Semua</button>
                </div>
            </div>
        </div>
    </section>

    <section class="px-4 pb-16 pt-10 sm:px-6 lg:px-8">
        <div id="services-grid" class="mx-auto grid max-w-7xl gap-6 md:grid-cols-2 xl:grid-cols-3">
            <div class="page-card page-border page-shadow rounded-3xl border p-5">
                <div class="page-surface flex h-32 items-center justify-center rounded-2xl">
                    <p class="page-muted text-sm">Memuat jasa...</p>
                </div>
            </div>
        </div>
    </section>

    <script>
        const servicesGrid = document.getElementById('services-grid');
        const filtersWrap = document.getElementById('category-filters');
        const searchInput = document.getElementById('service-search');
        let activeCategory = '';
        let debounceTimer;

        const formatCurrency = (value) => {
            if (!value) return 'Rp0';
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value);
        };

        const setActiveFilter = (category) => {
            activeCategory = category;
            const buttons = filtersWrap.querySelectorAll('button[data-category]');
            buttons.forEach((button) => {
                const isActive = button.dataset.category === category;
                button.classList.toggle('bg-[#F05A28]', isActive);
                button.classList.toggle('text-white', isActive);
                button.classList.toggle('border-transparent', isActive);
            });
        };

        const renderServices = (services = []) => {
            if (!services.length) {
                servicesGrid.innerHTML = `
                    <div class="page-card page-border page-shadow rounded-3xl border p-5">
                        <div class="page-surface flex h-32 items-center justify-center rounded-2xl">
                            <p class="page-muted text-sm">Belum ada jasa yang cocok.</p>
                        </div>
                    </div>`;
                return;
            }

            servicesGrid.innerHTML = services.map((service) => {
                const priceText = formatCurrency(service.price);
                const rating = service.average_rating ? Number(service.average_rating).toFixed(1) : '—';
                const reviewsCount = service.reviews_count ? `${service.reviews_count} review` : 'Belum ada review';
                const category = service.category?.name ?? 'Jasa';
                const seller = service.user?.name ?? 'Freelancer';
                return `
                    <article class="page-card page-border page-shadow rounded-3xl border p-5">
                        <div class="page-surface flex h-32 items-center justify-center rounded-2xl">
                            <div class="text-center px-3">
                                <p class="text-sm font-semibold text-[#F7931E]">${category}</p>
                                <p class="mt-2 text-xl font-semibold text-[#F05A28]">${service.title}</p>
                            </div>
                        </div>
                        <div class="mt-5">
                            <p class="page-muted text-sm">oleh ${seller}</p>
                            <div class="mt-3 flex items-center justify-between text-sm">
                                <span class="page-chip rounded-full px-3 py-1 text-xs font-semibold">⭐ ${rating}</span>
                                <span class="page-muted">${reviewsCount}</span>
                            </div>
                            <div class="mt-4 flex items-center justify-between">
                                <p class="text-lg font-semibold text-[#F05A28]">${priceText}</p>
                                <a href="/jasa/${service.id}" class="focus-ring inline-flex items-center gap-2 rounded-2xl bg-[#F05A28] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#F7931E]">
                                    Lihat detail
                                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </div>
                    </article>`;
            }).join('');

            lucide.createIcons();
        };

        const loadCategories = async () => {
            try {
                const response = await fetch('/api/categories', { headers: { 'Accept': 'application/json' } });
                const payload = await response.json();
                const categories = payload.data || [];
                const buttons = categories.map((category) => `
                    <button type="button" data-category="${category.slug}" class="page-chip rounded-full px-4 py-2 text-xs font-semibold">
                        ${category.name}
                    </button>
                `).join('');
                filtersWrap.insertAdjacentHTML('beforeend', buttons);
            } catch (error) {
                console.error('Gagal memuat kategori:', error);
            }
        };

        const loadServices = async (keyword = '') => {
            servicesGrid.innerHTML = `
                <div class="page-card page-border page-shadow rounded-3xl border p-5">
                    <div class="page-surface flex h-32 items-center justify-center rounded-2xl">
                        <p class="page-muted text-sm">Memuat jasa...</p>
                    </div>
                </div>`;

            const params = new URLSearchParams();
            if (activeCategory) {
                params.set('category', activeCategory);
            }
            if (keyword) {
                params.set('search', keyword);
            }

            try {
                const response = await fetch(`/api/services?${params.toString()}`, { headers: { 'Accept': 'application/json' } });
                const payload = await response.json();
                const services = payload.data?.data || [];
                renderServices(services);
            } catch (error) {
                console.error('Gagal memuat jasa:', error);
                renderServices([]);
            }
        };

        filtersWrap.addEventListener('click', (event) => {
            const button = event.target.closest('button[data-category]');
            if (!button) return;
            setActiveFilter(button.dataset.category);
            loadServices(searchInput.value.trim());
        });

        searchInput.addEventListener('input', (event) => {
            const value = event.target.value.trim();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => loadServices(value), 350);
        });

        setActiveFilter('');
        loadCategories();
        loadServices('');
    </script>
@endsection

@section('footer')
    @include('partials.marketplace-footer')
@endsection
