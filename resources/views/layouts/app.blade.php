<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Agnus Dei School Systems INC.')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/agnusbg.png') }}">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>

        * {margin: 0; padding: 0; box-sizing: border-box;}

        html, body { width: 100%; margin: 0; font-family: 'Poppins', sans-serif; color: #333; background-color: #f9fafb; box-sizing: border-box; overflow-x:hidden}

        /* Header */
        header { background: #001e82; padding: 20px clamp(20px,5vw,60px); display: flex; justify-content: space-between; align-items: center; color: #fff; width: 100%; flex-wrap: wrap;}
        .logo-link { display: flex; align-items: center; gap: 12px; text-decoration: none; color: white; }
        .logo-link img { height: 50px; }
        .nav-menu { display: flex; align-items: center; gap: 10px; flex-wrap: wrap}
        .nav-menu a { color: white; text-decoration: none; font-weight: 500; cursor: pointer; }
        .nav-menu a:hover { text-decoration: underline; }

        /* Dropdown */
        .dropdown { position: relative; }
        .dropbtn { color: white; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 5px; user-select: none; }
        .dropdown-content { position: absolute; top: 100%; left: 0; background: #fff; min-width: 180px; max-width: 90vw; border-radius: 8px; box-shadow: 0 12px 30px rgba(0,0,0,0.2); overflow: hidden; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.25s ease; z-index: 1000; }
        .dropdown-content a { display: block; padding: 12px 20px; color: #001e82; text-decoration: none; font-size: 14px; }
        .dropdown-content a:hover { background: #f1f5f9; }
        .dropdown.show .dropdown-content { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropbtn span { transition: transform 0.25s ease; }
        .dropdown.show .dropbtn span { transform: rotate(180deg); }

        /* Hero */
        .hero { background: linear-gradient(135deg, #001e82, #142c7e); color: #fff; text-align: center; max-width: 100%; padding: 160px 20px 120px 20px}
        .hero h2 { font-size: 42px; margin-bottom: 20px; }
        .hero p { font-size: 18px; max-width: 700px; margin: 0 auto 40px; line-height: 1.6; }
        .hero button { background: #fff; color: #142c7e; border: none; padding: 14px 30px; font-size: 16px; border-radius: 30px; cursor: pointer; font-weight: 600; }
        .hero button:hover { background: #e5e7eb; }

        img { max-width: 100%; height: auto; display: block; }

        /* Button */
        .btn-register { display: inline-block; background: #fff; color: #142c7e; padding: 14px 30px; font-size: 16px; border-radius: 30px; font-weight: 600; text-decoration: none; transition: background 0.3s ease; }
        .btn-register:hover { background: #e5e7eb; }

        /* Sections */
        .section { padding-top: 100px; padding-bottom: 100px ; max-width: 1100px; margin: auto; }
        .section + .section { margin-top: 50px; }
        .section h3 { font-size: 32px; text-align: center; margin-bottom: 20px; }
        .section p { font-size: 16px; line-height: 1.7; max-width: 800px; margin: 0 auto; }
        .features { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px,1fr)); gap: 30px; margin-top: 60px; }
        .feature-box { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); transition: transform 0.3s ease; }
        .feature-box:hover { transform: translateY(-5px); }
        .feature-box h4 { margin-bottom: 10px; font-size: 20px; }

        /* Footer */
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
        <a href="{{ route('home') }}">Home | </a>
        <div class="dropdown">
            <a class="dropbtn" onclick="toggleDropdown(event)">About Us<span>▾</span>| </a>
            <div class="dropdown-content">
                <a href="/philosophy">Educational Philosophy</a>
                <a href="/background">Institutional Background</a>
                <a href="/contact">Contact Information</a>
            </div>
        </div>
        <div class="dropdown">
            <a class="dropbtn" onclick="toggleDropdown(event)">Admissions<span>▾</span>| </a>
            <div class="dropdown-content">
                <a href="/program-offerings">Program Offerings</a>
                <a href="/requirements"> Requirements & Procedures</a>
                <a href="/discounts">Scholarship & Discounts</a>
            </div>
        </div>
        <a href="/register">Registration</a>

    </nav>
</header>

<main>
    @yield('content')
</main>

<footer>
    © 2026 Agnus Dei School Systems INC. | Website
</footer>


<script>
function toggleDropdown(event) {
    event.preventDefault();
    event.stopPropagation();

    const dropdown = event.currentTarget.parentElement;

    // Close any other open dropdowns
    document.querySelectorAll('.dropdown.show').forEach(function(dd) {
        if(dd !== dropdown) {  // don't close the current one
            dd.classList.remove('show');
        }
    });

    // Toggle the clicked dropdown
    dropdown.classList.toggle('show');
}

// Close dropdown when clicking outside
document.addEventListener('click', function() {
    document.querySelectorAll('.dropdown.show').forEach(function(dd) {
        dd.classList.remove('show');
    });
});
</script>


</body>
</html>
