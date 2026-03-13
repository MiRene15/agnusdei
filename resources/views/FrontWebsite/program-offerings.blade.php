@extends('layouts.app')

@section('title', 'Program Offerings - Agnus Dei School Systems INC.')

@section('content')

<section class="hero-program" >
    <h2>Program Offerings</h2>
</section>

<div class="page-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <h4>About Us</h4>
        <nav class="sidebar-nav">
            <a href="/program-offerings" class="active">Program Offerings</a>
            <a href="/requirements">Requirements & Procedures</a>
            <a href="/discounts">Student Discounts</a>
        </nav>

        <div class="accreditations">
            <h5>ACCREDITATIONS AND AFFILIATIONS</h5>
            <img src="{{ asset('images/accreditation1.png') }}" alt="Accreditation 1">
            <img src="{{ asset('images/accreditation2.png') }}" alt="Accreditation 2">
        </div>
    </aside>

    <!-- Main Content -->
    <section class="main-content">
        <div class="content-section">
    <h3 class="content-title">ACADEMIC PROGRAMS</h3>

    <div class="accordion">
        <button class="accordion-header">
            <span>Basic Education</span>
            <span class="accordion-icon">+</span>
        </button>

        <div class="accordion-content">
            <ul>
                <li>Complete Elementary Program: Kindergarten to Grade 6</li>
                <li>Junior High School: Grade 7 to Grade 10</li>
                <li>
                    Senior High School: Grade 11 to Grade 12
                    <ul>
                        <li><strong>Academic Tracks</strong>
                            <ul>
                                <li>STEM</li>
                                <li>ABM</li>
                                <li>HUMSS</li>
                                <li>GAS</li>
                            </ul>
                        </li>
                        <li><strong>TVL Tracks</strong>
                            <ul>
                                <li>ICT</li>
                                <li>Home Economics</li>
                                <li>Industrial Arts</li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>

/* Accordion */
.accordion {
    border: 1px solid #e0e0e0;
}

.accordion-header {
    width: 100%;
    background: linear-gradient(to bottom, #f7c46c, #f0a43a);
    border: none;
    padding: 12px 16px;
    font-size: 15px;
    font-weight: 600;
    color: #000;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.accordion-icon {
    font-size: 18px;
}

.accordion-content {
    display: none;
    padding: 15px 25px;
    background: #fff;
}

.accordion-content ul {
    padding-left: 20px;
}

.accordion-content li {
    margin-bottom: 6px;
    font-size: 15px;
}

.hero-program {
    background: linear-gradient(135deg, #001e82, #142c7e);
    color: #fff;
    text-align: center;
    min-height: 60px;   /* set your desired height */
    padding: 50px 20px;  /* optional extra padding */
    display: flex;
    justify-content: center;
    align-items: center;
}
.hero-program h2 {
    font-size: 36px;     /* you can resize the text separately */
}

.page-container {
    display: flex;
    gap: 40px;
    max-width: 1200px;
    margin: 50px auto;
    padding: 0 20px;
    flex-wrap: wrap;
}

/* Sidebar */
.sidebar {
    flex: 1;
    min-width: 220px;
}
.sidebar h4 {
    font-size: 16px;
    font-weight: 600;
    color: #142c7e;
    margin-bottom: 15px;
}
.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.sidebar-nav a {
    text-decoration: none;
    color: #001e82;
    font-weight: 500;
    transition: color 0.2s;
}
.sidebar-nav a:hover,
.sidebar-nav a.active {
    color: #007bff;
}
.accreditations {
    margin-top: 40px;
}
.accreditations h5 {
    font-size: 14px;
    color: #001e82;
    margin-bottom: 10px;
}
.accreditations img {
    width: 80px;
    display: block;
    margin-bottom: 10px;
}

/* Main Content */
.main-content {
    flex: 3;
    min-width: 600px;
    display: flex;
    flex-direction: column;
    gap: 30px;
}
.content-section h3.content-title {
    color: #142c7e;
    font-size: 18px;
    margin-bottom: 10px;
    text-transform: uppercase;
}
.content-section p {
    font-size: 16px;
    line-height: 1.7;
}

/* Responsive 
@media screen and (max-width: 900px) {
    .page-container {
        flex-direction: column;
    }
    .sidebar, .main-content {
        min-width: 100%;
    }
}*/
</style>

<script>
document.querySelectorAll('.accordion-header').forEach(button => {
    button.addEventListener('click', () => {
        const content = button.nextElementSibling;
        const icon = button.querySelector('.accordion-icon');

        if (content.style.display === 'block') {
            content.style.display = 'none';
            icon.textContent = '+';
        } else {
            content.style.display = 'block';
            icon.textContent = '−';
        }
    });
});
</script>


@endsection
