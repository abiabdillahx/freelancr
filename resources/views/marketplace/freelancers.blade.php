@extends('layouts.marketplace')

@section('title', 'Cari Freelancer')

@section('navbar')
    @include('partials.marketplace-nav')
@endsection

@section('content')
    <section class="px-4 pt-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="page-panel page-border rounded-[32px] border p-6 sm:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="mb-3 text-sm font-semibold uppercase tracking-[0.2em] text-[#F7931E]">Direktori bakat</p>
                        <h1 class="text-4xl font-semibold leading-tight text-[#F05A28] sm:text-5xl">
                            Temukan partner kerja yang tepat untuk proyek kamu.
                        </h1>
                        <p class="page-muted mt-4 text-base leading-7 sm:text-lg">
                            Daftar mahasiswa bertalenta yang siap bantu kebutuhan digital kamu.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="px-4 pb-16 pt-10 sm:px-6 lg:px-8">
        <div id="freelancers-grid" class="mx-auto grid max-w-7xl gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div class="page-card page-border page-shadow rounded-3xl border p-5">
                <p class="page-muted text-center text-sm">Memuat daftar freelancer...</p>
            </div>
        </div>
    </section>

    <script>
        (function () {
            const grid = document.getElementById('freelancers-grid');

            const loadFreelancers = async () => {
                try {
                    const response = await fetch('/api/freelancers', { headers: { 'Accept': 'application/json' } });
                    const result = await response.json();
                    
                    // Karena API pake paginate(), datanya ada di result.data.data
                    const freelancers = result.data?.data || [];

                    if (freelancers.length === 0) {
                        grid.innerHTML = '<p class="page-muted text-center col-span-full py-10">Belum ada freelancer terdaftar.</p>';
                        return;
                    }

                    grid.innerHTML = freelancers.map(user => `
                        <article class="page-card page-border page-shadow rounded-3xl border p-5 transition hover:border-[#F7931E]">
                            <div class="flex flex-col items-center text-center">
                                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-[#F05A28]/10 text-[#F05A28] mb-4">
                                    <i data-lucide="user" class="h-10 w-10"></i>
                                </div>
                                <h2 class="text-lg font-semibold text-[#F05A28]">${user.name}</h2>
                                <p class="page-muted mt-1 text-xs uppercase tracking-wider font-bold">FREELANCER</p>
                                <p class="page-muted mt-3 text-sm line-clamp-2">${user.bio || 'Freelancer kampus siap membantu proyek kamu.'}</p>
                                
                                <a href="/freelancer/${user.id}" class="focus-ring mt-5 inline-flex h-10 w-full items-center justify-center rounded-xl bg-[#F05A28]/10 px-4 text-xs font-bold text-[#F05A28] transition hover:bg-[#F05A28] hover:text-white">
                                    LIHAT PROFIL
                                </a>
                            </div>
                        </article>
                    `).join('');

                    lucide.createIcons();
                } catch (error) {
                    console.error('Error load freelancers:', error);
                    grid.innerHTML = '<p class="text-center text-red-500 col-span-full">Gagal memuat data.</p>';
                }
            };

            loadFreelancers();
        })();
    </script>
@endsection

@section('footer')
    @include('partials.marketplace-footer')
@endsection
