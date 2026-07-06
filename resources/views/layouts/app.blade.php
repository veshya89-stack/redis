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
            color:white;
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
        }

        .topbar{
            background:white;
            height:80px;
            box-shadow:0 2px 10px rgba(0,0,0,.05);
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:0 35px;
        }

        .page-title{
            font-size:30px;
            font-weight:700;
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

    </style>

</head>

<body>

<div class="sidebar">

    <div class="logo">
        <span>R</span>EDIS
    </div>

    <div class="subtitle">
        Retail Executive Decision Information System
    </div>

    <div class="menu">

        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active-menu' : '' }}">
            <i class="bi bi-grid"></i>
            Dashboard
        </a>

        <a href="{{ route('strategic-initiative.index') }}" class="{{ request()->routeIs('strategic-initiative.*', 'action-plan.*') ? 'active-menu' : '' }}">
            <i class="bi bi-bullseye"></i>
            Strategic Initiative
        </a>

        <a href="{{ route('executive-brief.index') }}" class="{{ request()->routeIs('executive-brief.*') ? 'active-menu' : '' }}">
            <i class="bi bi-file-earmark-text"></i>
            Executive Brief
        </a>

        <a href="{{ route('administration.index') }}" class="{{ request()->routeIs('administration.*') ? 'active-menu' : '' }}">
            <i class="bi bi-gear"></i>
            Administration
        </a>

    </div>

</div>

<div class="content">

    <div class="topbar">

        <div class="page-title">

            @yield('title')

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

</body>

</html>
