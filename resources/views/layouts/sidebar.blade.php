<div class="sidebar bg-gray-50 shadow-md rounded-lg p-4 w-64 overflow-y-auto" id="sidebar">
    <img src="{{ asset('C:\laragon\www\manejementsuratbaru\public\images\logo.png') }}" alt="Logo" class="w-16 h-16 mx-auto mb-2">
    <a class="navbar-brand fw-bold text-center text-xl mb-4 text-blue-600" href="{{ route('dashboard') }}" style="font-size: 25px; color: #3B82F6;">SIAP BRO!</a>
    <h2 class="text-lg font-bold mb-2">Menu Utama</h2>
    <ul class="list-none p-0">
        <li class="my-2">
            <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('dashboard') ? 'bg-blue-200' : '' }}" 
               href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt mr-2"></i> Beranda
            </a>
        </li>
        <li class="my-2">
            <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('surat-masuk.*') ? 'bg-blue-200' : '' }}" 
               href="#">
                <i class="fas fa-envelope mr-2"></i> Surat Umum
                <i class="fas fa-chevron-down ml-auto transform transition-transform duration-200" style="margin-left: auto;"></i>
            </a>
            <ul class="list-none pl-4 hidden">
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('surat-masuk.index') ? 'bg-blue-200' : '' }}" 
                       href="{{ route('surat-masuk.index') }}">
                        <i class="fas fa-envelope mr-2"></i> Surat Masuk
                    </a>
                </li>
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('surat-keluar.index') ? 'bg-blue-200' : '' }}" 
                       href="{{ route('surat-keluar.index') }}">
                        <i class="fas fa-paper-plane mr-2"></i> Surat Keluar
                    </a>
                </li>
            </ul>
        </li>
        <li class="my-2">
            <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('sppd.*') ? 'bg-blue-200' : '' }}" 
               href="#">
                <i class="fas fa-car mr-2"></i> SPPD
                <i class="fas fa-chevron-down ml-auto transform transition-transform duration-200" style="margin-left: auto;"></i>
            </a>
            <ul class="list-none pl-4 hidden">
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('sppd-dalam-daerah.*') ? 'bg-blue-200' : '' }}" 
                       href="{{ route('sppd-dalam-daerah.index') }}">
                        <i class="fas fa-car mr-2"></i> SPPD Dalam
                    </a>
                </li>
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('sppd-luar-daerah.*') ? 'bg-blue-200' : '' }}" 
                       href="{{ route('sppd-luar-daerah.index') }}">
                        <i class="fas fa-plane mr-2"></i> SPPD Luar
                    </a>
                </li>
            </ul>
        </li>
        <li class="my-2">
            <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('spt.*') ? 'bg-blue-200' : '' }}" 
               href="#">
                <i class="fas fa-file-signature mr-2"></i> SPT
                <i class="fas fa-chevron-down ml-auto transform transition-transform duration-200" style="margin-left: auto;"></i>
            </a>
            <ul class="list-none pl-4 hidden">
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('spt-dalam-daerah.*') ? 'bg-blue-200' : '' }}" 
                       href="{{ route('spt-dalam-daerah.index') }}">
                        <i class="fas fa-file-signature mr-2"></i> SPT Dalam
                    </a>
                </li>
                <li class="my-1">
                    <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('spt-luar-daerah.*') ? 'bg-blue-200' : '' }}" 
                       href="{{ route('spt-luar-daerah.index') }}">
                        <i class="fas fa-file-alt mr-2"></i> SPT Luar
                    </a>
                </li>
            </ul>
        </li>
        <li class="my-2">
            <a class="flex items-center p-2 rounded-lg hover:bg-blue-100 {{ request()->routeIs('buku-agenda.*') ? 'bg-blue-200' : '' }}" 
               href="{{ route('buku-agenda.index') }}">
                <i class="fas fa-book mr-2"></i> Buku Agenda
            </a>
        </li>
    </ul>
</div>

<script>
    document.querySelectorAll('.sidebar a[href="#"]').forEach(item => {
        item.addEventListener('click', event => {
            const submenu = item.nextElementSibling;
            submenu.classList.toggle('hidden');
        });
    });
</script>
