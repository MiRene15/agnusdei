<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Agnus Dei School Systems INC.')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/agnusbg.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { width: 100%; margin: 0; font-family: 'Poppins', sans-serif; color: #333; background-color: #f9fafb; overflow-x: hidden; }
        header { background: #001e82; padding: 20px clamp(20px,5vw,60px); display: flex; justify-content: space-between; align-items: center; color: #fff; width: 100%; flex-wrap: wrap; }
        .logo-link { display: flex; align-items: center; gap: 12px; text-decoration: none; color: white; }
        .logo-link img { height: 50px; }
        .nav-menu { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .nav-menu a { color: white; text-decoration: none; font-weight: 500; cursor: pointer; }
        .nav-menu a:hover { text-decoration: underline; }
        .dropdown { position: relative; }
        .dropbtn { color: white; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; user-select: none; }
        .dropdown-content { position: absolute; top: 100%; left: 0; background: #fff; min-width: 220px; max-width: 90vw; border-radius: 8px; box-shadow: 0 12px 30px rgba(0,0,0,0.2); overflow: hidden; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.25s ease; z-index: 1000; }
        .dropdown-content a { display: block; padding: 12px 20px; color: #001e82; text-decoration: none; font-size: 14px; }
        .dropdown-content a:hover { background: #f1f5f9; }
        .dropdown.show .dropdown-content { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropbtn span { transition: transform 0.25s ease; }
        .dropdown.show .dropbtn span { transform: rotate(180deg); }
        footer { background: #001e82; color: #cbd5e1; text-align: center; padding: 30px 20px; margin-top: 80px; }
    </style>
</head>
<body>
<header>
    <a href="{{ url('/') }}" class="logo-link">
        <img src="{{ asset('images/agnusbg.png') }}" alt="agnusbg">
        <h2>Agnus Dei School Systems INC.</h2>
    </a>
    <nav class="nav-menu">
        <a href="{{ route('home') }}">Home |</a>
        <div class="dropdown">
            <a class="dropbtn" onclick="toggleDropdown(event)">About Us <span>v</span> |</a>
            <div class="dropdown-content">
                <a href="/philosophy">Educational Philosophy</a>
                <a href="/background">Institutional Background</a>
                <a href="/contact">Contact Information</a>
            </div>
        </div>
        <div class="dropdown">
            <a class="dropbtn" onclick="toggleDropdown(event)">Admissions <span>v</span> |</a>
            <div class="dropdown-content">
                <a href="/program-offerings">Program Offerings</a>
                <a href="/requirements">Requirements and Procedures</a>
                <a href="/discounts">Discounts and Privileges</a>
            </div>
        </div>
        <div class="dropdown">
            <a class="dropbtn" onclick="toggleDropdown(event)">Account Portal <span>v</span></a>
            <div class="dropdown-content">
                <a href="{{ route('register') }}">Student Portal</a>
                <a href="{{ route('staff.register') }}">Staff Portal</a>
                <a href="{{ route('login') }}">Login</a>
            </div>
        </div>
    </nav>
</header>

<main>
    @yield('content')
</main>

<footer>
    &copy; 2026 Agnus Dei School Systems INC. | Website
</footer>

<script>
function toggleDropdown(event) {
    event.preventDefault();
    event.stopPropagation();
    const dropdown = event.currentTarget.parentElement;
    document.querySelectorAll('.dropdown.show').forEach(function(dd) {
        if (dd !== dropdown) {
            dd.classList.remove('show');
        }
    });
    dropdown.classList.toggle('show');
}

document.addEventListener('click', function() {
    document.querySelectorAll('.dropdown.show').forEach(function(dd) {
        dd.classList.remove('show');
    });
});
</script>
</body>
</html>
