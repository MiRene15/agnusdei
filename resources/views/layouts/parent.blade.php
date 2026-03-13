<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Parent Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7fb;
            display: flex;
            min-height: 100vh;
            color: #1e293b;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #001e82 0%, #0c2fa0 100%);
            padding: 28px 18px;
            display: flex;
            flex-direction: column;
            box-shadow: 6px 0 20px rgba(0,0,0,0.08);
        }

        .brand-box {
            color: #fff;
            margin-bottom: 30px;
            padding: 10px 8px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }

        .brand-box h2 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .brand-box p {
            font-size: 12px;
            opacity: 0.85;
            line-height: 1.5;
        }

        .menu-label {
            color: rgba(255,255,255,0.75);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 16px 10px 10px;
        }

        .sidebar a,
        .sidebar button {
            color: #ffffff;
            text-decoration: none;
            padding: 13px 14px;
            border-radius: 12px;
            margin-bottom: 8px;
            font-size: 14px;
            transition: 0.25s ease;
            border: none;
            background: transparent;
            text-align: left;
            width: 100%;
            font-family: inherit;
            cursor: pointer;
        }

        .sidebar a:hover,
        .sidebar button:hover {
            background: rgba(255,255,255,0.12);
        }

        .sidebar a.active {
            background: #ffffff;
            color: #001e82;
            font-weight: 600;
            box-shadow: 0 10px 20px rgba(0,0,0,0.10);
        }

        .logout-wrap {
            margin-top: auto;
            padding-top: 12px;
        }

        .logout-btn {
            background: rgba(255,255,255,0.12) !important;
        }

        .logout-btn:hover {
            background: #ffffff !important;
            color: #001e82 !important;
        }

        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .topbar {
            background: #ffffff;
            padding: 22px 34px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border-bottom: 1px solid #e2e8f0;
        }

        .topbar h3 {
            color: #001e82;
            font-weight: 700;
            font-size: 24px;
        }

        .topbar .welcome {
            color: #475569;
            font-size: 14px;
            font-weight: 500;
        }

        .content {
            padding: 30px;
        }

        .page-intro {
            margin-bottom: 24px;
        }

        .page-intro h4 {
            color: #0f172a;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .page-intro p {
            color: #64748b;
            font-size: 14px;
        }

        .card {
            background: #ffffff;
            padding: 24px;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);
            margin-bottom: 24px;
            border: 1px solid #eef2f7;
        }

        .card h4 {
            color: #001e82;
            margin-bottom: 15px;
            font-weight: 700;
            font-size: 18px;
        }

        .btn {
            display: inline-block;
            padding: 11px 16px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s ease;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .btn-primary {
            background: #001e82;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #1636a3;
        }

        .btn-outline {
            border: 2px solid #001e82;
            color: #001e82;
            background: #ffffff;
        }

        .btn-outline:hover {
            background: #001e82;
            color: #ffffff;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);
            border: 1px solid #eef2f7;
        }

        .stat-label {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 30px;
            font-weight: 700;
            color: #001e82;
        }

        .stat-sub {
            margin-top: 8px;
            color: #94a3b8;
            font-size: 12px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            min-width: 760px;
        }

        th {
            text-align: left;
            padding: 14px;
            background: #001e82;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            color: #334155;
            vertical-align: middle;
        }

        tr:hover td {
            background: #f8fbff;
        }

        @media (max-width: 991px) {
            .sidebar {
                width: 220px;
            }

            .topbar {
                padding: 18px 20px;
            }

            .content {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="brand-box">
        <h2>Parent Portal</h2>
        <p>Agnus Dei School Systems ERP</p>
    </div>

    <div class="menu-label">Main Menu</div>

    <a href="{{ route('parent.dashboard') }}"
       class="{{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
        Dashboard
    </a>

    <a href="{{ route('parent.children') }}"
       class="{{ request()->routeIs('parent.children') ? 'active' : '' }}">
        My Children
    </a>

    <a href="{{ route('parent.grades') }}"
       class="{{ request()->routeIs('parent.grades') ? 'active' : '' }}">
        Grades
    </a>

    <a href="{{ route('parent.billing') }}"
       class="{{ request()->routeIs('parent.billing') ? 'active' : '' }}">
        Billing
    </a>

    <div class="logout-wrap">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</div>

<div class="main">
    <div class="topbar">
        <h3>@yield('title', 'Parent Dashboard')</h3>
        <div class="welcome">Welcome, Parent</div>
    </div>

    <div class="content">
        @yield('content')
    </div>
</div>

</body>
</html>