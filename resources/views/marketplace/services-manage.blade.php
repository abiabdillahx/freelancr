@extends('layouts.marketplace')

@section('title', 'Kelola Jasa Saya')

@section('navbar')
    @include('partials.marketplace-nav')
@endsection

@section('content')
    <section class="px-4 pt-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="page-panel page-border rounded-[32px] border p-6 sm:p-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#F7931E]">Freelancer Tools</p>
                        <h1 class="mt-3 text-4xl font-semibold text-[#F05A28] sm:text-5xl">Kelola Jasa Kamu</h1>
                        <p class="page-muted mt-4 text-base">Tambah atau perbarui layanan jasa yang kamu tawarkan kepada client.</p>
                    </div>
                    <button onclick="openCreateModal()" class="focus-ring inline-flex h-12 items-center gap-2 rounded-2xl bg-[#F05A28] px-6 text-sm font-semibold text-white transition hover:bg-[#F7931E]">
                        <i data-lucide="plus" class="h-5 w-5"></i>
                        Tambah Jasa Baru
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="px-4 pb-16 pt-10 sm:px-6 lg:px-8">
        <div id="my-services-list" class="mx-auto max-w-7xl grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Data jasa freelancer akan dimuat di sini -->
            <div class="col-span-full py-10 text-center page-muted">Memuat jasa kamu...</div>
        </div>
    </section>

    <!-- Modal Form (Create/Edit) -->
    <div id="service-modal" class="fixed inset-0 z-50 flex hidden items-center justify-center bg-[#111111]/90 p-4 backdrop-blur-sm">
        <div class="w-full max-w-2xl rounded-[32px] border border-[#2A2A2A] bg-[#171717] p-8 overflow-y-auto max-h-[90vh]">
            <h2 id="modal-title" class="text-2xl font-semibold text-[#F05A28]">Tambah Jasa Baru</h2>
            
            <form id="service-form" class="mt-6 space-y-4">
                <input type="hidden" id="service-id">
                
                <div>
                    <label class="mb-2 block text-sm font-semibold text-[#F5F5F4]">Judul Jasa</label>
                    <input type="text" id="form-title" required class="w-full rounded-2xl border border-[#2A2A2A] bg-[#111111] px-4 py-3 text-[#F5F5F4] outline-none focus:border-[#F7931E]" placeholder="Contoh: Desain Logo Minimalis Cepat">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#F5F5F4]">Kategori</label>
                        <select id="form-category" required class="w-full rounded-2xl border border-[#2A2A2A] bg-[#111111] px-4 py-3 text-[#F5F5F4] outline-none focus:border-[#F7931E]">
                            <option value="">Pilih Kategori</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#F5F5F4]">Harga (IDR)</label>
                        <input type="number" id="form-price" required class="w-full rounded-2xl border border-[#2A2A2A] bg-[#111111] px-4 py-3 text-[#F5F5F4] outline-none focus:border-[#F7931E]" placeholder="50000">
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-[#F5F5F4]">Deskripsi</label>
                    <textarea id="form-description" rows="4" required class="w-full rounded-2xl border border-[#2A2A2A] bg-[#111111] p-4 text-[#F5F5F4] outline-none focus:border-[#F7931E]" placeholder="Jelaskan apa saja yang akan client dapatkan..."></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal()" class="flex-1 rounded-2xl border border-[#2A2A2A] py-3 text-sm font-semibold text-[#F5F5F4]">Batal</button>
                    <button type="submit" id="save-btn" class="flex-1 rounded-2xl bg-[#F05A28] py-3 text-sm font-semibold text-white">Simpan Jasa</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const token = localStorage.getItem('freelancr_token');
            const listContainer = document.getElementById('my-services-list');
            const serviceForm = document.getElementById('service-form');
            const categorySelect = document.getElementById('form-category');

            if (!token) { window.location.href = '/login'; return; }

            const loadCategories = async () => {
                try {
                    const res = await fetch('/api/categories');
                    const result = await res.json();
                    categorySelect.innerHTML = '<option value="">Pilih Kategori</option>' + 
                        result.data.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
                } catch (e) { console.error(e); }
            };

            const loadMyServices = async () => {
                try {
                    // Kita asumsikan ada endpoint atau filter untuk ambil jasa milik sendiri
                    // Karena /api/services belum ada filter user_id via query, kita ambil semua dan filter di client
                    // atau hit endpoint yang sama tapi dapet data owner.
                    const user = JSON.parse(localStorage.getItem('freelancr_user') || '{}');
                    const res = await fetch('/api/services');
                    const result = await res.json();
                    
                    // Filter jasa yang milik user ini
                    const myServices = (result.data?.data || []).filter(s => s.user?.name === user.name);

                    if (myServices.length === 0) {
                        listContainer.innerHTML = '<div class="col-span-full py-10 text-center page-muted">Kamu belum memposting jasa apapun.</div>';
                        return;
                    }

                    listContainer.innerHTML = myServices.map(s => `
                        <div class="page-card page-border rounded-3xl border p-5">
                            <h3 class="text-lg font-semibold text-[#F05A28]">${s.title}</h3>
                            <p class="text-xs font-bold text-[#F7931E] mt-1">${s.category?.name || 'Kategori'}</p>
                            <p class="mt-3 text-sm page-muted line-clamp-2">${s.description}</p>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-sm font-bold text-[#F5F5F4]">Rp ${s.price.toLocaleString('id-ID')}</span>
                                <div class="flex gap-2">
                                    <button onclick="deleteService(${s.id})" class="p-2 text-red-500 hover:bg-red-500/10 rounded-xl transition">
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    lucide.createIcons();
                } catch (e) { console.error(e); }
            };

            window.openCreateModal = () => {
                serviceForm.reset();
                document.getElementById('service-id').value = '';
                document.getElementById('modal-title').textContent = 'Tambah Jasa Baru';
                document.getElementById('service-modal').classList.remove('hidden');
            };

            window.closeModal = () => {
                document.getElementById('service-modal').classList.add('hidden');
            };

            window.deleteService = async (id) => {
                if (!confirm('Yakin ingin menghapus jasa ini?')) return;
                try {
                    const res = await fetch(`/api/services/${id}`, {
                        method: 'DELETE',
                        headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                    });
                    if (res.ok) loadMyServices();
                    else alert('Gagal menghapus jasa.');
                } catch (e) { console.error(e); }
            };

            serviceForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const btn = document.getElementById('save-btn');
                btn.disabled = true;
                btn.textContent = 'Menyimpan...';

                const data = {
                    title: document.getElementById('form-title').value,
                    category_id: document.getElementById('form-category').value,
                    price: document.getElementById('form-price').value,
                    description: document.getElementById('form-description').value,
                };

                try {
                    const res = await fetch('/api/services', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify(data)
                    });

                    if (res.ok) {
                        closeModal();
                        loadMyServices();
                    } else {
                        const err = await res.json();
                        alert(err.message || 'Gagal menyimpan jasa.');
                    }
                } catch (e) { console.error(e); }
                finally {
                    btn.disabled = false;
                    btn.textContent = 'Simpan Jasa';
                }
            });

            loadCategories();
            loadMyServices();
        })();
    </script>
@endsection
