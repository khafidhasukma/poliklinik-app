<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Poliklinik' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Instrument+Serif:ital@0;1&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    @vite(['resources/js/app.js','resources/css/app.css'])
</head>

<body>

    <div class="app-wrapper">

        {{-- SIDEBAR --}}
        <div id="appSidebar" class="sidebar-fixed">
            @include('components.partials.sidebar')
        </div>

        {{-- OVERLAY --}}
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        {{-- MAIN --}}
        <div class="main-content">

            @include('components.partials.header')

            <div class="main-scroll">

                @if(session('success'))
                <div class="alert alert-success mb-4 rounded-xl shadow-sm auto-dismiss">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if(session('message') && session('type') === 'success')
                <div class="alert alert-success mb-4 rounded-xl shadow-sm auto-dismiss">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('message') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-error mb-4 rounded-xl shadow-sm auto-dismiss">
                    <i class="fas fa-circle-xmark"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                @if(session('message') && session('type') === 'error')
                <div class="alert alert-error mb-4 rounded-xl shadow-sm auto-dismiss">
                    <i class="fas fa-circle-xmark"></i>
                    <span>{{ session('message') }}</span>
                </div>
                @endif

                {{ $slot }}

            </div>

            @include('components.partials.footer')

        </div>

    </div>

    <script>
        function toggleSidebar(){
            const sidebar = document.getElementById('appSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const icon    = document.getElementById('sidebarToggleIcon');

            if (window.innerWidth >= 1024) {
                // Desktop: collapse/expand sidebar width
                const isCollapsed = sidebar.classList.toggle('collapsed');
                if (icon) {
                    icon.className = isCollapsed ? 'fas fa-bars-staggered w-5 h-5' : 'fas fa-bars w-5 h-5';
                }
            } else {
                // Mobile: slide in/out with overlay
                const isOpen = sidebar.classList.toggle('open');
                overlay.style.display = isOpen ? 'block' : 'none';
                if (icon) {
                    icon.className = isOpen ? 'fas fa-xmark w-5 h-5' : 'fas fa-bars w-5 h-5';
                }
            }
        }

        function toggleFullscreen(){
            const icon=document.getElementById('fsIcon')

            if(!document.fullscreenElement){
                document.documentElement.requestFullscreen()
                icon.className='fas fa-compress'
            }
            else{
                document.exitFullscreen()
                icon.className='fas fa-expand'
            }
        }

        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.auto-dismiss').forEach(function (el) {
                setTimeout(function () {
                    el.style.transition = 'opacity 0.4s ease';
                    el.style.opacity = '0';
                    setTimeout(function () { el.remove(); }, 400);
                }, 5000);
            });
        });
    </script>

    @stack('scripts')

</body>

</html>