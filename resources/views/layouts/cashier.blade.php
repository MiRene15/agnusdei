<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Cashier Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Poppins', sans-serif; background:#f4f7fb; display:flex; align-items:flex-start; min-height:100vh; color:#1e293b; }
        .sidebar { width:260px; min-width:260px; position:sticky; top:0; height:100vh; overflow-y:auto; background:linear-gradient(180deg, #001e82 0%, #0c2fa0 100%); padding:28px 18px; display:flex; flex-direction:column; box-shadow:6px 0 20px rgba(0,0,0,0.08); }
        .brand-box { color:#fff; margin-bottom:30px; padding:10px 8px 20px; border-bottom:1px solid rgba(255,255,255,0.15); }
        .brand-box h2 { font-size:20px; font-weight:700; margin-bottom:6px; }
        .brand-box p { font-size:12px; opacity:0.85; line-height:1.5; }
        .menu-label { color:rgba(255,255,255,0.75); font-size:11px; text-transform:uppercase; letter-spacing:1px; margin:16px 10px 10px; }
        .sidebar a, .sidebar button { color:#ffffff; text-decoration:none; padding:13px 14px; border-radius:12px; margin-bottom:8px; font-size:14px; transition:0.25s ease; border:none; background:transparent; text-align:left; width:100%; font-family:inherit; cursor:pointer; }
        .sidebar a:hover, .sidebar button:hover { background:rgba(255,255,255,0.12); }
        .sidebar a.active { background:#ffffff; color:#001e82; font-weight:600; box-shadow:0 10px 20px rgba(0,0,0,0.10); }
        .logout-wrap { padding-top:12px; }
        .logout-btn { background:rgba(255, 8, 49, 0.808) !important; }
        .logout-btn:hover { background:#ffffff !important; color:#001e82 !important; }
        .main { flex:1; display:flex; flex-direction:column; min-width:0; }
        .topbar { background:#ffffff; padding:22px 34px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 4px 20px rgba(0,0,0,0.05); border-bottom:1px solid #e2e8f0; }
        .topbar h3 { color:#001e82; font-weight:700; font-size:24px; }
        .topbar .welcome { color:#475569; font-size:14px; font-weight:500; }
        .content { padding:30px; }
        .page-intro { margin-bottom:24px; }
        .page-intro h4 { color:#0f172a; font-size:22px; font-weight:700; margin-bottom:6px; }
        .page-intro p { color:#64748b; font-size:14px; }
        .card { background:#ffffff; padding:24px; border-radius:18px; box-shadow:0 10px 25px rgba(15, 23, 42, 0.05); margin-bottom:24px; border:1px solid #eef2f7; }
        .card h4 { color:#001e82; margin-bottom:15px; font-weight:700; font-size:18px; }
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:18px; margin-bottom:24px; }
        .stat-card { background:#ffffff; border-radius:18px; padding:22px; box-shadow:0 10px 25px rgba(15, 23, 42, 0.05); border:1px solid #eef2f7; }
        .stat-label { color:#64748b; font-size:13px; margin-bottom:10px; }
        .stat-value { font-size:30px; font-weight:700; color:#001e82; }
        .stat-sub { margin-top:8px; color:#94a3b8; font-size:12px; }
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; margin-top:8px; min-width:760px; }
        th { text-align:left; padding:14px; background:#001e82; color:#ffffff; font-size:13px; font-weight:600; }
        td { padding:14px; border-bottom:1px solid #e5e7eb; font-size:14px; color:#334155; vertical-align:middle; }
        tr:hover td { background:#f8fbff; }
        .badge { display:inline-block; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:600; }
        .badge-paid { background:#dcfce7; color:#166534; }
        .badge-pending { background:#fef3c7; color:#92400e; }
        .badge-overdue { background:#fee2e2; color:#991b1b; }
        .btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:12px 18px; border-radius:14px; text-decoration:none; font-size:14px; font-weight:700; transition:0.22s ease; border:1px solid transparent; cursor:pointer; font-family:inherit; box-shadow:0 10px 24px rgba(15, 23, 42, 0.08); }
        .btn-primary { background:linear-gradient(135deg, #0b3fc7, #001e82); color:#ffffff; }
        .btn-primary:hover { transform:translateY(-1px); box-shadow:0 16px 28px rgba(0, 30, 130, 0.18); }
        .btn-outline { border-color:#bfdbfe; color:#0f3ca8; background:linear-gradient(180deg, #ffffff, #f8fbff); }
        .btn-outline:hover { border-color:#60a5fa; background:#eff6ff; }
        .form-control, .search-row input, .search-row select, input[type='text'], input[type='password'], input[type='email'], input[type='date'], input[type='number'], textarea, select {
            width:100%; padding:13px 15px; border:1px solid #d7e2f0; border-radius:14px; font-size:14px; background:linear-gradient(180deg, #ffffff, #f8fbff); outline:none; color:#0f172a; box-shadow:inset 0 1px 2px rgba(15, 23, 42, 0.04);
        }
        .form-control:focus, .search-row input:focus, .search-row select:focus, input:focus, textarea:focus, select:focus { border-color:#2563eb; box-shadow:0 0 0 4px rgba(37,99,235,0.12); }
        .search-row { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:18px; }
        .quick-actions { display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:14px; }
        .action-box { display:block; background:linear-gradient(180deg, #ffffff, #f8fbff); border:1px solid #dbeafe; border-radius:18px; padding:18px 18px 16px; text-decoration:none; color:#0f172a; transition:0.22s ease; box-shadow:0 10px 25px rgba(15, 23, 42, 0.06); }
        .action-box:hover { transform:translateY(-3px); box-shadow:0 18px 34px rgba(0,30,130,0.10); border-color:#60a5fa; }
        .action-box h5 { color:#001e82; margin-bottom:8px; font-size:16px; }
        .action-box p { color:#64748b; font-size:13px; line-height:1.55; }
        @media (max-width: 991px) { .sidebar { width:220px; min-width:220px; } .topbar { padding:18px 20px; } .content { padding:20px; } }
        @media (max-width: 768px) { body { flex-direction:column; } .sidebar { width:100%; min-width:100%; height:auto; position:relative; } }
    .loading-screen { position:fixed; inset:0; background:rgba(244, 247, 251, 0.9); backdrop-filter:blur(4px); display:flex; align-items:center; justify-content:center; flex-direction:column; gap:14px; z-index:9999; opacity:0; pointer-events:none; transition:opacity .22s ease; }
.loading-screen.active { opacity:1; pointer-events:auto; }
.loading-spinner { width:54px; height:54px; border-radius:50%; border:4px solid rgba(37,99,235,.16); border-top-color:#0b3fc7; animation:spin .8s linear infinite; }
.loading-label { color:#0f172a; font-weight:700; letter-spacing:.04em; }
@keyframes spin { to { transform:rotate(360deg); } }
</style>
</head>
<body>
<div class="loading-screen active" id="loading-screen"><div class="loading-spinner"></div><div class="loading-label">Loading, please wait...</div></div>
<div class="sidebar">
    <div class="brand-box"><h2>Cashier Portal</h2><p>Agnus Dei School Systems ERP</p></div>
    <div class="menu-label">Main Menu</div>
    <a href="{{ route('cashier.dashboard') }}" class="{{ request()->routeIs('cashier.dashboard') ? 'active' : '' }}">Dashboard</a>
    <a href="{{ route('cashier.payments') }}" class="{{ request()->routeIs('cashier.payments') ? 'active' : '' }}">Payments</a>
    <a href="{{ route('cashier.billing') }}" class="{{ request()->routeIs('cashier.billing') ? 'active' : '' }}">Billing</a>
    <a href="{{ route('cashier.reports') }}" class="{{ request()->routeIs('cashier.reports') ? 'active' : '' }}">Reports</a>
    <a href="{{ route('cashier.settings') }}" class="{{ request()->routeIs('cashier.settings*') ? 'active' : '' }}">Settings</a>
    <div class="logout-wrap"><form method="POST" action="{{ route('logout') }}">@csrf <button type="submit" class="logout-btn">Logout</button></form></div>
</div>
<div class="main">
    <div class="topbar"><h3>@yield('title', 'Cashier Dashboard')</h3><div class="welcome">Welcome, Cashier</div></div>
    <div class="content">@yield('content')</div>
</div>
<script>
(function () {
    function sanitizeNumericInputs(root) {
        (root || document).querySelectorAll('input[type="number"]').forEach(function (input) {
            input.addEventListener('keydown', function (event) {
                if (['e', 'E', '+', '-'].includes(event.key)) {
                    event.preventDefault();
                }
            });
            input.addEventListener('input', function () {
                var allowDecimal = (input.step || '').includes('.') || input.dataset.decimal === 'true';
                var value = input.value;
                value = allowDecimal ? value.replace(/[^\d.]/g, '') : value.replace(/\D/g, '');
                if (allowDecimal) {
                    var parts = value.split('.');
                    value = parts.shift() + (parts.length ? '.' + parts.join('') : '');
                }
                input.value = value;
            });
        });
        (root || document).querySelectorAll('input[inputmode="numeric"]').forEach(function (input) {
            input.addEventListener('input', function () {
                input.value = input.value.replace(/\D/g, '');
            });
        });
    }
    document.addEventListener('DOMContentLoaded', function () { sanitizeNumericInputs(document); });
})();
</script>
<script>
(function () {
    function bindLoadingScreen() {
        var loadingScreen = document.getElementById('loading-screen');
        if (!loadingScreen) { return; }
        window.addEventListener('load', function () { loadingScreen.classList.remove('active'); });
        document.querySelectorAll('a[href]').forEach(function (link) {
            link.addEventListener('click', function (event) {
                var href = link.getAttribute('href') || '';
                if (!href || href.startsWith('#') || link.target === '_blank' || event.ctrlKey || event.metaKey) { return; }
                loadingScreen.classList.add('active');
            });
        });
        document.querySelectorAll('form').forEach(function (form) {
            form.addEventListener('submit', function () { loadingScreen.classList.add('active'); });
        });
    }
    document.addEventListener('DOMContentLoaded', bindLoadingScreen);
})();
</script></body>
</html>

