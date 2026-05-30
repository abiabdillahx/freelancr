@php
    $footerColumns = $footerColumns ?? [
        [
            'title' => 'Produk',
            'links' => [
                ['label' => 'Jasa', 'href' => route('services')],
                ['label' => 'Freelancer', 'href' => route('freelancers')],
                ['label' => 'Pasang proyek', 'href' => route('projects.create')],
            ],
        ],
        [
            'title' => 'Marketplace',
            'links' => [
                ['label' => 'Pesanan', 'href' => route('orders')],
                ['label' => 'Dashboard', 'href' => route('dashboard')],
                ['label' => 'Masuk', 'href' => route('login')],
            ],
        ],
        [
            'title' => 'Legal',
            'links' => [
                ['label' => 'Syarat & ketentuan', 'href' => '#'],
                ['label' => 'Kebijakan privasi', 'href' => '#'],
                ['label' => 'Keamanan akun', 'href' => '#'],
            ],
        ],
        [
            'title' => 'Bantuan',
            'links' => [
                ['label' => 'Pusat bantuan', 'href' => '#'],
                ['label' => 'Kontak tim', 'href' => '#'],
                ['label' => 'Laporkan masalah', 'href' => '#'],
            ],
        ],
    ];

    $footerSocials = $footerSocials ?? [
        ['label' => 'Instagram', 'href' => '#', 'icon' => 'instagram'],
        ['label' => 'LinkedIn', 'href' => '#', 'icon' => 'linkedin'],
        ['label' => 'YouTube', 'href' => '#', 'icon' => 'youtube'],
    ];
@endphp

@include('partials.site-footer', [
    'left' => 'Freelancr. Marketplace jasa freelance kampus.',
    'right' => 'Temukan talent kampus untuk tugas digital dan proyek praktis kamu.',
    'columns' => $footerColumns,
    'socials' => $footerSocials,
])
