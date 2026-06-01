@extends('layouts.marketplace')

@section('title', 'Profil Freelancer')

@section('navbar')
    @include('partials.marketplace-nav')
@endsection

@section('content')
    <section class="px-4 pt-10 sm:px-6 lg:px-8">
        <div id="freelancer-detail" class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="page-panel page-border rounded-[32px] border p-6 sm:p-8">
                <p class="text-center page-muted">Memuat profil...</p>
            </div>
        </div>
    </section>

    <script>
        (function () {
            const container = document.getElementById('freelancer-detail');
            const freelancerId = window.location.pathname.split('/').filter(Boolean).pop();

            const loadDetail = async () => {
                try {
                    const response = await fetch(`/api/freelancers/${freelancerId}`, { headers: { 'Accept': 'application/json' } });
                    const result = await response.json();
                    const user = result.data;

                    if (!user) {
                        container.innerHTML = '<div class="page-panel page-border rounded-[32px] border p-10 text-center"><p class="text-red-500">Freelancer tidak ditemukan.</p></div>';
                        return;
                    }

                    container.innerHTML = `
                        <div class="page-panel page-border rounded-[32px] border p-6 sm:p-8">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#F7931E]">Profil freelancer</p>
                                    <h1 class="mt-3 text-4xl font-semibold text-[#F05A28] sm:text-5xl">${user.name}</h1>
                                    <p class="page-muted mt-3 text-base">Freelancer Kampus</p>
                                </div>
                                <div class="page-chip rounded-2xl px-4 py-2 text-xs font-semibold">⭐ 5.0</div>
                            </div>

                            <p class="page-muted mt-6 text-base leading-7">
                                ${user.bio || 'Freelancer bertalenta yang siap membantu proyek digital dan kebutuhan kampus kamu dengan hasil terbaik.'}
                            </p>

                            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                                <div class="page-surface page-border rounded-2xl border p-4">
                                    <p class="page-muted text-xs font-semibold uppercase tracking-[0.2em]">Email Kontak</p>
                                    <p class="mt-2 text-base font-semibold text-[#F05A28]">${user.email}</p>
                                </div>
                                <div class="page-surface page-border rounded-2xl border p-4">
                                    <p class="page-muted text-xs font-semibold uppercase tracking-[0.2em]">Status</p>
                                    <p class="mt-2 text-base font-semibold text-[#22C55E]">AKTIF</p>
                                </div>
                            </div>

                            <div class="mt-10">
                                <h2 class="text-xl font-semibold text-[#F05A28]">Jasa yang ditawarkan</h2>
                                <div id="freelancer-services" class="mt-4 grid gap-4 md:grid-cols-2">
                                    <p class="page-muted text-sm">Mengambil daftar jasa...</p>
                                </div>
                            </div>
                        </div>

                        <aside class="space-y-4">
                            <div class="page-card page-border page-shadow rounded-[28px] border p-6">
                                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#F7931E]">Mulai kerja</p>
                                <p class="page-muted mt-3 text-sm">Ajak freelancer ini untuk proyek kamu.</p>
                                <button class="focus-ring mt-5 inline-flex h-12 w-full items-center justify-center rounded-2xl bg-[#F05A28] px-4 text-sm font-semibold text-white transition hover:bg-[#F7931E]">
                                    Ajukan kerja sama
                                </button>
                                <button class="page-outline focus-ring mt-3 inline-flex h-12 w-full items-center justify-center rounded-2xl border px-4 text-sm font-semibold">
                                    Chat dulu
                                </button>
                            </div>
                            <div class="page-surface page-border rounded-2xl border p-5 text-center">
                                <p class="page-muted text-xs font-semibold uppercase tracking-[0.2em]">Response Time</p>
                                <p class="mt-2 text-lg font-semibold text-[#F05A28]">Cepat ( < 1 jam )</p>
                            </div>
                        </aside>
                    `;

                    // Fetch services of this freelancer
                    const servicesRes = await fetch(`/api/services?search=${user.name}`, { headers: { 'Accept': 'application/json' } });
                    const servicesResult = await servicesRes.json();
                    const services = servicesResult.data?.data || [];
                    const servicesContainer = document.getElementById('freelancer-services');
                    
                    if (services.length === 0) {
                        servicesContainer.innerHTML = '<p class="page-muted text-sm italic">Belum mempublikasikan jasa.</p>';
                    } else {
                        servicesContainer.innerHTML = services.map(s => `
                            <a href="/jasa/${s.id}" class="page-surface page-border block rounded-2xl border p-4 hover:border-[#F7931E] transition">
                                <p class="text-sm font-semibold text-[#F05A28]">${s.title}</p>
                                <p class="mt-2 text-xs font-bold text-[#F7931E]">Rp ${s.price.toLocaleString('id-ID')}</p>
                            </a>
                        `).join('');
                    }

                    lucide.createIcons();
                } catch (error) {
                    console.error('Error load detail:', error);
                    container.innerHTML = '<p class="text-center text-red-500 col-span-full">Gagal memuat profil.</p>';
                }
            };

            loadDetail();
        })();
    </script>
@endsection

@section('footer')
    @include('partials.marketplace-footer')
@endsection
