@extends('layouts.marketplace')

@section('title', 'Pasang Proyek')

@section('navbar')
    @include('partials.marketplace-nav')
@endsection

@section('content')
    <section class="px-4 pt-10 sm:px-6 lg:px-8">
        <div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="page-panel page-border rounded-[32px] border p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#F7931E]">Pasang proyek</p>
                <h1 class="mt-3 text-4xl font-semibold leading-tight text-[#F05A28] sm:text-5xl">
                    Ceritakan kebutuhanmu, biar freelancer cepat ambil.
                </h1>
                <p class="page-muted mt-4 text-base leading-7 sm:text-lg">
                    Isi detail proyek dengan jelas supaya kamu dapat kandidat yang tepat.
                </p>

                <form class="mt-8 space-y-5">
                    <div>
                        <label class="page-text mb-2 block text-sm font-semibold">Judul proyek</label>
                        <div class="page-input page-border field-shell flex h-12 items-center gap-3 rounded-2xl border px-4">
                            <i data-lucide="briefcase" class="h-5 w-5 text-[#F7931E]"></i>
                            <input type="text" placeholder="Contoh: Desain landing page acara kampus" class="h-full flex-1 bg-transparent text-sm font-medium">
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="page-text mb-2 block text-sm font-semibold">Kategori</label>
                            <div class="page-input page-border field-shell flex h-12 items-center gap-3 rounded-2xl border px-4">
                                <i data-lucide="layers" class="h-5 w-5 text-[#F7931E]"></i>
                                <select class="h-full flex-1 bg-transparent text-sm font-medium">
                                    <option>Desain</option>
                                    <option>Website</option>
                                    <option>Video</option>
                                    <option>Copywriting</option>
                                    <option>Data</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="page-text mb-2 block text-sm font-semibold">Deadline</label>
                            <div class="page-input page-border field-shell flex h-12 items-center gap-3 rounded-2xl border px-4">
                                <i data-lucide="calendar" class="h-5 w-5 text-[#F7931E]"></i>
                                <input type="text" placeholder="Contoh: 7 hari" class="h-full flex-1 bg-transparent text-sm font-medium">
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="page-text mb-2 block text-sm font-semibold">Budget min</label>
                            <div class="page-input page-border field-shell flex h-12 items-center gap-3 rounded-2xl border px-4">
                                <i data-lucide="wallet" class="h-5 w-5 text-[#F7931E]"></i>
                                <input type="text" placeholder="Rp150.000" class="h-full flex-1 bg-transparent text-sm font-medium">
                            </div>
                        </div>
                        <div>
                            <label class="page-text mb-2 block text-sm font-semibold">Budget max</label>
                            <div class="page-input page-border field-shell flex h-12 items-center gap-3 rounded-2xl border px-4">
                                <i data-lucide="wallet" class="h-5 w-5 text-[#F7931E]"></i>
                                <input type="text" placeholder="Rp400.000" class="h-full flex-1 bg-transparent text-sm font-medium">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="page-text mb-2 block text-sm font-semibold">Detail proyek</label>
                        <div class="page-input page-border field-shell flex min-h-[140px] items-start gap-3 rounded-2xl border px-4 py-3">
                            <i data-lucide="file-text" class="mt-1 h-5 w-5 text-[#F7931E]"></i>
                            <textarea placeholder="Jelaskan kebutuhan, style, dan file yang harus disiapkan." class="min-h-[120px] w-full bg-transparent text-sm font-medium"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="focus-ring inline-flex h-12 w-full items-center justify-center rounded-2xl bg-[#F05A28] px-4 text-sm font-semibold text-white transition hover:bg-[#F7931E]">
                        Pasang proyek sekarang
                    </button>
                </form>
            </div>

            <aside class="space-y-4">
                <div class="page-card page-border page-shadow rounded-[28px] border p-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#F7931E]">Tips singkat</p>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="page-muted flex items-start gap-3">
                            <i data-lucide="check-circle-2" class="mt-0.5 h-5 w-5 text-[#F7931E]"></i>
                            Jelaskan tujuan dan output yang kamu mau.
                        </li>
                        <li class="page-muted flex items-start gap-3">
                            <i data-lucide="check-circle-2" class="mt-0.5 h-5 w-5 text-[#F7931E]"></i>
                            Cantumkan deadline biar freelancer bisa atur waktu.
                        </li>
                        <li class="page-muted flex items-start gap-3">
                            <i data-lucide="check-circle-2" class="mt-0.5 h-5 w-5 text-[#F7931E]"></i>
                            Upload referensi kalau ada (logo, warna, contoh).
                        </li>
                    </ul>
                </div>

                <div class="page-surface page-border rounded-2xl border p-5">
                    <p class="page-muted text-xs font-semibold uppercase tracking-[0.2em]">Respon cepat</p>
                    <p class="mt-2 text-lg font-semibold text-[#F05A28]">72% freelancer balas dalam 1 jam</p>
                    <p class="page-muted mt-2 text-sm">Tinggal tunggu penawaran masuk.</p>
                </div>
            </aside>
        </div>
    </section>
@endsection

@section('footer')
    @include('partials.marketplace-footer')
@endsection
