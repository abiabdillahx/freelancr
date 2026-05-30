@extends('layouts.marketplace')

@section('title', 'Profil Saya')

@section('navbar')
    @include('partials.marketplace-nav')
@endsection

@section('content')
    <section class="px-4 pt-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="page-panel page-border rounded-[32px] border p-6 sm:p-8">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-center">
                    <!-- Avatar Placeholder -->
                    <div class="flex h-32 w-32 shrink-0 items-center justify-center rounded-[32px] bg-[#F05A28]/10 border-2 border-[#F7931E]/20 text-[#F05A28]">
                        <i data-lucide="user" class="h-16 w-16"></i>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 id="user-name" class="text-4xl font-semibold text-[#F05A28] sm:text-5xl">Memuat...</h1>
                            <span id="user-role" class="rounded-full border border-[#F7931E]/40 bg-[#F7931E]/10 px-4 py-1 text-xs font-bold uppercase tracking-wider text-[#F7931E]">Role</span>
                        </div>
                        <p id="user-email" class="page-muted mt-2 text-lg">email@example.com</p>
                        <p id="user-bio" class="page-muted mt-4 max-w-2xl text-base leading-7">
                            Sedang mengambil bio singkat kamu...
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                        <a id="manage-services-link" href="/jasa-saya" class="hidden focus-ring inline-flex h-11 items-center justify-center gap-2 rounded-2xl bg-[#F7931E] px-6 text-sm font-semibold text-white transition hover:bg-[#F05A28]">
                            <i data-lucide="layers" class="h-4 w-4"></i>
                            Kelola Jasa Saya
                        </a>
                        <button class="focus-ring inline-flex h-11 items-center justify-center gap-2 rounded-2xl bg-[#F05A28] px-6 text-sm font-semibold text-white transition hover:bg-[#F7931E]">
                            <i data-lucide="edit-3" class="h-4 w-4"></i>
                            Edit Profil
                        </button>
                        <button id="logout-btn-profile" class="focus-ring inline-flex h-11 items-center justify-center gap-2 rounded-2xl border border-[#2A2A2A] bg-transparent px-6 text-sm font-semibold text-red-500 transition hover:bg-red-500/10">
                            <i data-lucide="log-out" class="h-4 w-4"></i>
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="px-4 pt-10 sm:px-6 lg:px-8">
        <div class="mx-auto grid max-w-7xl gap-6 md:grid-cols-3">
            <div class="page-card page-border page-shadow rounded-3xl border p-6">
                <p class="page-muted text-xs font-semibold uppercase tracking-[0.2em]">Total Transaksi</p>
                <p id="stat-orders" class="mt-3 text-3xl font-semibold text-[#F05A28]">0</p>
            </div>
            <div class="page-card page-border page-shadow rounded-3xl border p-6">
                <p class="page-muted text-xs font-semibold uppercase tracking-[0.2em]">Rating Kamu</p>
                <p class="mt-3 text-3xl font-semibold text-[#F05A28]">⭐ 5.0</p>
            </div>
            <div id="role-specific-stat" class="page-card page-border page-shadow rounded-3xl border p-6">
                <p class="page-muted text-xs font-semibold uppercase tracking-[0.2em]">Bergabung Sejak</p>
                <p id="stat-join" class="mt-3 text-xl font-semibold text-[#F05A28]">-</p>
            </div>
        </div>
    </section>

    <section class="px-4 pb-16 pt-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="page-card page-border page-shadow rounded-3xl border p-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-semibold text-[#F05A28]">Aktivitas Akun</h2>
                    <a href="/pesanan" class="text-sm font-semibold text-[#F7931E] hover:underline">Lihat semua pesanan</a>
                </div>
                
                <div id="recent-activity" class="space-y-4">
                    <div class="page-surface page-border rounded-2xl border p-6 text-center">
                        <p class="page-muted text-sm">Belum ada aktivitas terbaru.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        (function () {
            const token = localStorage.getItem('freelancr_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            const loadProfile = async () => {
                try {
                    const response = await fetch('/api/auth/profile', {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        }
                    });

                    if (!response.ok) throw new Error('Unauthorized');

                    const result = await response.json();
                    const user = result.data.user;

                    document.getElementById('user-name').textContent = user.name;
                    document.getElementById('user-email').textContent = user.email;
                    document.getElementById('user-role').textContent = user.role;
                    document.getElementById('user-bio').textContent = user.bio || 'Belum ada bio. Klik Edit Profil untuk menambahkan.';
                    
                    if (user.role === 'freelancer') {
                        document.getElementById('manage-services-link').classList.remove('hidden');
                    }
                    
                    const joinDate = new Date(user.created_at).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
                    document.getElementById('stat-join').textContent = joinDate;

                    // Update UI based on role
                    if (user.role === 'freelancer') {
                        document.getElementById('role-specific-stat').querySelector('p:first-child').textContent = 'Jasa Diterbitkan';
                        // Ideally we'd fetch the count of services here
                        document.getElementById('stat-join').textContent = 'Freelancer Aktif';
                        document.getElementById('stat-join').classList.add('text-2xl');
                    }

                    // Fetch orders to update stats
                    const ordersRes = await fetch('/api/orders', {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        }
                    });
                    const ordersResult = await ordersRes.json();
                    const orders = ordersResult.data || [];
                    document.getElementById('stat-orders').textContent = orders.length;

                    if (orders.length > 0) {
                        const recent = orders.slice(0, 3);
                        document.getElementById('recent-activity').innerHTML = recent.map(order => `
                            <div class="page-surface page-border flex items-center justify-between rounded-2xl border p-4">
                                <div>
                                    <p class="font-semibold text-[#F05A28]">${order.service?.title || 'Pesanan'}</p>
                                    <p class="page-muted mt-1 text-xs">Status: ${order.status.toUpperCase()}</p>
                                </div>
                                <a href="/pesanan" class="text-xs font-bold text-[#F7931E]">DETAIL</a>
                            </div>
                        `).join('');
                    }

                    lucide.createIcons();
                } catch (error) {
                    console.error('Failed to load profile:', error);
                    localStorage.removeItem('freelancr_token');
                    window.location.href = '/login';
                }
            };

            const logoutBtn = document.getElementById('logout-btn-profile');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', () => {
                    localStorage.removeItem('freelancr_token');
                    localStorage.removeItem('freelancr_user');
                    window.location.href = '/';
                });
            }

            loadProfile();
        })();
    </script>
@endsection

@section('footer')
    @include('partials.marketplace-footer')
@endsection
