@extends('layouts.marketplace')

@section('title', 'Pesanan Saya')

@section('navbar')
    @include('partials.marketplace-nav')
@endsection

@section('content')
    <section class="px-4 pt-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="page-panel page-border rounded-[32px] border p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#F7931E]">Manajemen Pesanan</p>
                <h1 class="mt-3 text-4xl font-semibold text-[#F05A28] sm:text-5xl">
                    Pantau alur kerja kamu di sini.
                </h1>
                <p class="page-muted mt-4 text-base leading-7 sm:text-lg">
                    Update status pengerjaan (Freelancer) atau beri ulasan (Client) setelah selesai.
                </p>
            </div>
        </div>
    </section>

    <section class="px-4 pb-16 pt-10 sm:px-6 lg:px-8">
        <div id="orders-list" class="mx-auto max-w-7xl space-y-6">
            <div class="page-card page-border page-shadow rounded-3xl border p-10 text-center">
                <p class="page-muted text-base">Memuat pesanan...</p>
            </div>
        </div>
    </section>

    <!-- Modal Review (Hidden by default) -->
    <div id="review-modal" class="fixed inset-0 z-50 flex hidden items-center justify-center bg-[#111111]/90 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-[32px] border border-[#2A2A2A] bg-[#171717] p-8">
            <h2 class="text-2xl font-semibold text-[#F05A28]">Beri Ulasan</h2>
            <p class="page-muted mt-2 text-sm">Bagaimana hasil kerja freelancer ini?</p>
            
            <form id="review-form" class="mt-6 space-y-4">
                <input type="hidden" id="review-order-id">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-[#F5F5F4]">Rating (1-5)</label>
                    <select id="review-rating" class="w-full rounded-2xl border border-[#2A2A2A] bg-[#111111] px-4 py-3 text-[#F5F5F4] outline-none focus:border-[#F7931E]">
                        <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                        <option value="4">⭐⭐⭐⭐ (Puas)</option>
                        <option value="3">⭐⭐⭐ (Biasa Saja)</option>
                        <option value="2">⭐⭐ (Kurang)</option>
                        <option value="1">⭐ (Buruk)</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-[#F5F5F4]">Komentar</label>
                    <textarea id="review-comment" rows="3" class="w-full rounded-2xl border border-[#2A2A2A] bg-[#111111] p-4 text-[#F5F5F4] outline-none focus:border-[#F7931E]" placeholder="Hasilnya bagus banget, sesuai brief!"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" id="close-review-modal" class="flex-1 rounded-2xl border border-[#2A2A2A] py-3 text-sm font-semibold text-[#F5F5F4]">Batal</button>
                    <button type="submit" id="submit-review-btn" class="flex-1 rounded-2xl bg-[#F05A28] py-3 text-sm font-semibold text-white">Kirim Review</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const ordersList = document.getElementById('orders-list');
            const token = localStorage.getItem('freelancr_token');
            const userData = JSON.parse(localStorage.getItem('freelancr_user') || '{}');
            const isFreelancer = userData.role === 'freelancer';

            const getStatusBadge = (status) => {
                switch (status) {
                    case 'pending': return 'text-[#F7931E] border-[#F7931E]/40 bg-[#F7931E]/10';
                    case 'in_progress': return 'text-[#38BDF8] border-[#38BDF8]/40 bg-[#38BDF8]/10';
                    case 'completed': return 'text-[#22C55E] border-[#22C55E]/40 bg-[#22C55E]/10';
                    case 'cancelled': return 'text-red-500 border-red-500/40 bg-red-500/10';
                    default: return 'text-gray-400 border-gray-400/40 bg-gray-400/10';
                }
            };

            const updateOrderStatus = async (orderId, status) => {
                if (!confirm(`Ubah status pesanan ke ${status.replace('_', ' ')}?`)) return;
                
                try {
                    const response = await fetch(`/api/orders/${orderId}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify({ status })
                    });
                    if (response.ok) loadOrders();
                    else alert('Gagal update status.');
                } catch (error) { console.error(error); }
            };

            const cancelOrder = async (orderId) => {
                if (!confirm('Yakin ingin membatalkan pesanan ini?')) return;
                try {
                    const response = await fetch(`/api/orders/${orderId}/cancel`, {
                        method: 'PUT',
                        headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                    });
                    if (response.ok) loadOrders();
                    else alert('Gagal membatalkan pesanan.');
                } catch (error) { console.error(error); }
            };

            const loadOrders = async () => {
                if (!token) { window.location.href = '/login'; return; }
                try {
                    const response = await fetch('/api/orders', {
                        headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                    });
                    const result = await response.json();
                    const orders = result.data || [];

                    if (orders.length === 0) {
                        ordersList.innerHTML = '<div class="page-card page-border page-shadow rounded-3xl border p-10 text-center"><p class="page-muted">Belum ada pesanan.</p></div>';
                        return;
                    }

                    ordersList.innerHTML = orders.map(order => {
                        const actions = [];
                        if (isFreelancer) {
                            if (order.status === 'pending') actions.push(`<button onclick="updateOrderStatus(${order.id}, 'in_progress')" class="rounded-xl bg-blue-500 px-4 py-2 text-xs font-bold text-white">TERIMA KERJA</button>`);
                            if (order.status === 'in_progress') actions.push(`<button onclick="updateOrderStatus(${order.id}, 'completed')" class="rounded-xl bg-green-500 px-4 py-2 text-xs font-bold text-white">SELESAI</button>`);
                        } else {
                            if (order.status === 'pending') actions.push(`<button onclick="cancelOrder(${order.id})" class="rounded-xl border border-red-500/50 px-4 py-2 text-xs font-bold text-red-500">BATALKAN</button>`);
                            if (order.status === 'completed' && !order.review) actions.push(`<button onclick="openReviewModal(${order.id})" class="rounded-xl bg-[#F7931E] px-4 py-2 text-xs font-bold text-white">BERI REVIEW</button>`);
                        }

                        return `
                            <div class="page-card page-border page-shadow rounded-3xl border p-6">
                                <div class="flex flex-wrap items-start justify-between gap-4">
                                    <div class="flex gap-4">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#F05A28]/10 text-[#F05A28]">
                                            <i data-lucide="${isFreelancer ? 'user' : 'briefcase'}" class="h-6 w-6"></i>
                                        </div>
                                        <div>
                                            <p class="text-lg font-semibold text-[#F05A28]">${order.service?.title || 'Jasa'}</p>
                                            <p class="page-muted text-sm">${isFreelancer ? 'Client: ' + (order.client?.name || 'User') : 'Freelancer: ' + (order.service?.user?.name || 'Talent')}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="rounded-full border px-4 py-1.5 text-xs font-bold ${getStatusBadge(order.status)}">${order.status.toUpperCase()}</span>
                                        <p class="mt-2 text-[10px] text-gray-500">${new Date(order.created_at).toLocaleString()}</p>
                                    </div>
                                </div>
                                <div class="mt-6 border-t border-[#2A2A2A] pt-4 flex flex-wrap items-center justify-between gap-4">
                                    <p class="page-muted text-sm italic">"${order.note || 'Tidak ada catatan.'}"</p>
                                    <div class="flex gap-2">
                                        ${actions.join('')}
                                        <button class="rounded-xl border border-[#2A2A2A] px-4 py-2 text-xs font-bold text-gray-400">CHAT</button>
                                    </div>
                                </div>
                                ${order.review ? `
                                    <div class="mt-4 rounded-2xl bg-[#22C55E]/5 border border-[#22C55E]/20 p-4">
                                        <p class="text-[10px] font-bold text-[#22C55E] uppercase tracking-wider">Ulasan Kamu</p>
                                        <p class="mt-1 text-sm text-[#F5F5F4]">⭐ ${order.review.rating}/5 — "${order.review.comment}"</p>
                                    </div>
                                ` : ''}
                            </div>
                        `;
                    }).join('');

                    lucide.createIcons();
                } catch (error) { console.error(error); }
            };

            // Global exposure for onclick
            window.updateOrderStatus = updateOrderStatus;
            window.cancelOrder = cancelOrder;
            window.openReviewModal = (id) => {
                document.getElementById('review-order-id').value = id;
                document.getElementById('review-modal').classList.remove('hidden');
            };

            document.getElementById('close-review-modal').addEventListener('click', () => {
                document.getElementById('review-modal').classList.add('hidden');
            });

            document.getElementById('review-form').addEventListener('submit', async (e) => {
                e.preventDefault();
                const btn = document.getElementById('submit-review-btn');
                btn.disabled = true;
                btn.textContent = 'Mengirim...';

                try {
                    const response = await fetch('/api/reviews', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify({
                            order_id: document.getElementById('review-order-id').value,
                            rating: document.getElementById('review-rating').value,
                            comment: document.getElementById('review-comment').value
                        })
                    });

                    if (response.ok) {
                        document.getElementById('review-modal').classList.add('hidden');
                        loadOrders();
                    } else {
                        const err = await response.json();
                        alert(err.message || 'Gagal kirim review.');
                    }
                } catch (error) { console.error(error); }
                finally {
                    btn.disabled = false;
                    btn.textContent = 'Kirim Review';
                }
            });

            loadOrders();
        })();
    </script>
@endsection

@section('footer')
    @include('partials.marketplace-footer')
@endsection
