<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>REDIS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>

        body{
            background:#F4F7FC;
            overflow-x:hidden;
            font-size:17px;
            color:#2A2E37;
        }

        .sidebar{
            width:290px;
            background:#082A5E;
            min-height:100vh;
            position:fixed;
            top:0;
            left:0;
            color:white;
            z-index:1050;
            transition:transform .3s ease;
        }

        .logo{
            font-size:42px;
            font-weight:bold;
            padding:30px 25px 10px;
        }

        .logo span{
            color:#49A5FF;
        }

        .subtitle{
            padding:0 25px;
            font-size:15px;
            opacity:.75;
            line-height:22px;
        }

        .menu{
            margin-top:40px;
        }

        .menu a{
            display:block;
            color:white;
            text-decoration:none;
            padding:14px 25px;
            margin:8px 15px;
            border-radius:12px;
            transition:.3s;
        }

        .menu a:hover{
            background:#1E5EFF;
        }

        .active-menu{
            background:#1E5EFF;
        }

        .content{
            margin-left:290px;
            transition:margin-left .3s ease;
        }

        .topbar{
            background:white;
            height:80px;
            box-shadow:0 2px 10px rgba(0,0,0,.05);
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:0 35px;
            position:sticky;
            top:0;
            z-index:900;
        }

        .topbar-left{
            display:flex;
            align-items:center;
            gap:18px;
        }

        .menu-toggle{
            display:none;
            background:none;
            border:none;
            font-size:26px;
            color:#082A5E;
            cursor:pointer;
            padding:4px 6px;
        }

        .page-title{
            font-size:30px;
            font-weight:700;
        }

        .sidebar-overlay{
            display:none;
            position:fixed;
            inset:0;
            background:rgba(8,42,94,.45);
            z-index:1040;
        }

        .main-content{
            padding:32px 40px;
        }

        .card-body{
            padding:1.75rem 2rem;
        }

        h3.fw-bold{
            font-size:1.75rem;
            letter-spacing:-.01em;
        }

        h5.fw-bold, h6.fw-bold{
            font-size:1.05rem;
            letter-spacing:-.01em;
        }

        .text-muted{
            font-size:1rem;
        }

        small, .small{
            font-size:.92rem;
        }

        .badge{
            font-size:.88rem;
            font-weight:600;
            padding:.55em 1em;
            border-radius:8px;
            letter-spacing:.01em;
        }

        .progress{
            border-radius:20px;
            background:#EDF0F6;
        }

        .progress-bar{
            border-radius:20px;
        }

        .form-control, .form-select{
            border-radius:10px;
            border-color:#DEE2EC;
            font-size:1.02rem;
        }

        .form-control::placeholder{
            font-size:1rem;
        }

        .btn{
            border-radius:10px;
            font-size:1.02rem;
        }

        .table{
            font-size:1rem;
        }

        .table thead th{
            font-size:.8rem;
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:.05em;
            color:#8A8F9C;
            border-bottom:2px solid #EEF1F6;
            padding:1rem .9rem;
            white-space:nowrap;
        }

        .table td{
            padding:1rem .9rem;
            vertical-align:middle;
            border-color:#F1F3F8;
            font-size:1rem;
        }

        .table td:nth-child(2){
            min-width:240px;
            font-weight:500;
        }

        .breadcrumb{
            font-size:.9rem;
        }

        /* ===== Responsive: tablet & mobile ===== */
        @media (max-width: 768px){

            body{
                font-size:15px;
            }

            .sidebar{
                transform:translateX(-100%);
            }

            .sidebar.sidebar-open{
                transform:translateX(0);
            }

            .sidebar-overlay.overlay-visible{
                display:block;
            }

            .content{
                margin-left:0;
            }

            .menu-toggle{
                display:inline-block;
            }

            .topbar{
                height:64px;
                padding:0 16px;
            }

            .page-title{
                font-size:20px;
            }

            .main-content{
                padding:20px 16px;
            }

            .card-body{
                padding:1.1rem 1.2rem;
            }

            /* stack card grids on mobile */
            .row > [class*="col-"]{
                margin-bottom:14px;
            }

            .table-responsive{
                border-radius:10px;
            }
        }

    </style>

</head>

<body>

<div class="menu">

    <a href="{{ route('dashboard') }}"
       class="{{ request()->routeIs('dashboard') ? 'active-menu' : '' }}">
        <i class="bi bi-grid"></i>
        Dashboard
    </a>

    <a href="{{ route('strategic-initiative.index') }}"
       class="{{ request()->routeIs('strategic-initiative.*', 'action-plan.*') ? 'active-menu' : '' }}">
        <i class="bi bi-bullseye"></i>
        Strategic Initiative
    </a>

    <a href="{{ route('penugasan.index') }}"
       class="{{ request()->routeIs('penugasan.*') ? 'active-menu' : '' }}">
        <i class="bi bi-kanban"></i>
        Penugasan
    </a>

    <a href="{{ route('executive-brief.index') }}"
       class="{{ request()->routeIs('executive-brief.*') ? 'active-menu' : '' }}">
        <i class="bi bi-file-earmark-text"></i>
        Executive Brief
    </a>

    <a href="{{ route('administration.index') }}"
       class="{{ request()->routeIs('administration.*') ? 'active-menu' : '' }}">
        <i class="bi bi-gear"></i>
        Administration
    </a>

</div>

<div class="content" id="content">

    <div class="topbar">

        <div class="topbar-left">

            <button class="menu-toggle" id="menuToggle" aria-label="Buka menu">
                <i class="bi bi-list"></i>
            </button>

            <div class="page-title">

                @yield('title')

            </div>

        </div>

        <div>

            <i class="bi bi-bell fs-5 me-4"></i>

            <i class="bi bi-person-circle fs-4"></i>

            Bidang ED

        </div>

    </div>

    <div class="main-content">

        @yield('content')

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('menuToggle');

    function openSidebar(){
        sidebar.classList.add('sidebar-open');
        overlay.classList.add('overlay-visible');
    }

    function closeSidebar(){
        sidebar.classList.remove('sidebar-open');
        overlay.classList.remove('overlay-visible');
    }

    toggleBtn.addEventListener('click', function(){
        if(sidebar.classList.contains('sidebar-open')){
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    overlay.addEventListener('click', closeSidebar);

    // Tutup sidebar otomatis kalau menu diklik (mobile)
    document.querySelectorAll('.menu a').forEach(function(link){
        link.addEventListener('click', function(){
            if(window.innerWidth <= 768){
                closeSidebar();
            }
        });
    });
</script>

</body>

</html>
