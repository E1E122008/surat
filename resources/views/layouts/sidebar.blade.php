<div class="sidebar bg-gray-50 shadow-md rounded-lg p-4 w-64 overflow-y-auto" id="sidebar">
    <!-- Ikon Tutup Sidebar -->
    <button class="close-sidebar" onclick="closeSidebar()" style="position: absolute; top: 10px; right: 10px; background: none; border: none; cursor: pointer;">
        <i class="fas fa-times" style="font-size: 24px; color: #ffffff;"></i>
    </button>
    
    <!-- Logo Container -->
    <div class="logo-container text-center mb-6">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-image mx-auto mb-2">
        <a class="navbar-brand fw-bold text-center text-xl text-primary hover:opacity-80 transition-opacity duration-200" style="color: #ffffff !important;" href="{{ route('dashboard') }}">
            SIAP BROH !!!
        </a>
    </div>

    <h2 class="text-lg font-bold mb-2">Menu Utama</h2>
    <ul class="list-none p-0">
        <li class="my-2">
            <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
               href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt mr-2"></i> Beranda
            </a>
        </li>
        <li class="my-2">
            <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}" 
               href="#">
                <i class="fas fa-envelope mr-2"></i> Surat Umum
                <i class="fas fa-chevron-down ml-auto transform transition-transform duration-200" style="margin-left: auto;"></i>
            </a>
            <ul class="list-none pl-4 hidden">
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('surat-masuk.index') ? 'active' : '' }}" 
                       href="{{ route('surat-masuk.index') }}">
                        <i class="fas fa-envelope mr-2"></i> Surat Masuk
                    </a>
                </li>
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('surat-keluar.index') ? 'active' : '' }}" 
                       href="{{ route('surat-keluar.index') }}">
                        <i class="fas fa-paper-plane mr-2"></i> Surat Keluar
                    </a>
                </li>
            </ul>
        </li>
        <li class="my-2">
            <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('draft-phd.*') ? 'active' : '' }}" 
               href="#">
                <i class="fas fa-scroll mr-2"></i> Regis Draft PHD
                <i class="fas fa-chevron-down ml-auto transform transition-transform duration-200" style="margin-left: auto;"></i>
            </a>
            <ul class="list-none pl-4 hidden">
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('draft-phd.sk.*') ? 'active' : '' }}" 
                       href="{{ route('draft-phd.sk.index') }}">
                        <i class="fas fa-gavel mr-2"></i> SK
                    </a>
                </li>
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('draft-phd.perda.*') ? 'active' : '' }}" 
                       href="{{ route('draft-phd.perda.index') }}">
                        <i class="fas fa-scroll mr-2"></i> PERDA
                    </a>
                </li>
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('draft-phd.pergub.*') ? 'active' : '' }}" 
                       href="{{ route('draft-phd.pergub.index') }}">
                        <i class="fas fa-scroll mr-2"></i> PERGUB
                    </a>
                </li>
            </ul>
        </li>
        <li class="my-2">
            <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('spt.*') ? 'active' : '' }}" 
               href="#">
                <i class="fas fa-file-signature mr-2"></i> SPT
                <i class="fas fa-chevron-down ml-auto transform transition-transform duration-200" style="margin-left: auto;"></i>
            </a>
            <ul class="list-none pl-4 hidden">
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('spt-dalam-daerah.*') ? 'active' : '' }}" 
                       href="{{ route('spt-dalam-daerah.index') }}">
                        <i class="fas fa-file-signature mr-2"></i> SPT DD
                    </a>
                </li>
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('spt-luar-daerah.*') ? 'active' : '' }}" 
                       href="{{ route('spt-luar-daerah.index') }}">
                        <i class="fas fa-file-alt mr-2"></i> SPT LD
                    </a>
                </li>
            </ul>
        </li>
        <li class="my-2">
            <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('sppd.*') ? 'active' : '' }}" 
               href="#">
                <i class="fas fa-car mr-2"></i> SPPD
                <i class="fas fa-chevron-down ml-auto transform transition-transform duration-200" style="margin-left: auto;"></i>
            </a>
            <ul class="list-none pl-4 hidden">
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('sppd-dalam-daerah.*') ? 'active' : '' }}" 
                       href="{{ route('sppd-dalam-daerah.index') }}">
                        <i class="fas fa-car mr-2"></i> SPPD DD
                    </a>
                </li>
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('sppd-luar-daerah.*') ? 'active' : '' }}" 
                       href="{{ route('sppd-luar-daerah.index') }}">
                        <i class="fas fa-plane mr-2"></i> SPPD LD
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    <h2 class="text-lg font-bold mb-2">Menu Tambahan</h2>
    <ul class="list-none p-0">
        <li class="my-2">
            <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('buku-agenda.*') ? 'active' : '' }}" 
                href="#">
                 <i class="fas fa-file-signature mr-2"></i> Arsip
                 <i class="fas fa-chevron-down ml-auto transform transition-transform duration-200" style="margin-left: auto;"></i>
             </a>
             <ul class="list-none pl-4 hidden">
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('buku-agenda.index') ? 'active' : '' }}" 
                       href="{{ route('buku-agenda.index') }}">
                        <i class="fas fa-book mr-2"></i>Surat Masuk
                    </a>
                </li>
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('buku-agenda.kategori-keluar.index') ? 'active' : '' }}" 
                       href="{{ route('buku-agenda.kategori-keluar.index') }}">
                        <i class="fas fa-book mr-2"></i>Surat Keluar
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</div>

<script>
    // Pastikan fungsi closeSidebar sudah terdefinisi di sini
    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.remove('active');
        // Tambahkan logika untuk menghapus overlay jika ada
    }

    // Event listener untuk menu dropdown
    document.querySelectorAll('.sidebar a[href="#"]').forEach(item => {
        item.addEventListener('click', event => {
            const submenu = item.nextElementSibling;
            submenu.classList.toggle('hidden');
            
            // Putar ikon chevron
            const iconChevron = item.querySelector('.fa-chevron-down');
            iconChevron.classList.toggle('rotate-180');
        });
    });
</script>

<style>
    /* Logo Styling */
    .logo-container {
        padding: 0.75rem 0;
        margin-bottom: 1rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .logo-image {
        width: auto;
        height: 85px; /* Ukuran diperbesar menjadi 65px */
        max-width: 100%; /* Memperbesar lebar maksimal */
        object-fit: contain;
        margin-bottom: 0.5rem;
        transition: transform 0.3s ease;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .logo-image {
            height: 58px; /* Ukuran untuk mobile juga disesuaikan */
        }
    }

    /* Style lainnya tetap sama */
    .logo-image:hover {
        transform: scale(1.05);
    }

    .navbar-brand {
        font-size: 1.25rem;
        margin-top: 0.25rem;
    }
</style>
