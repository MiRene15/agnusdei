@extends('layouts.app')

@section('title', 'Contact Information - Agnus Dei School Systems INC.')

@section('content')

<section class="hero hero-contact">
    <h2>Contact Information</h2>
</section>


<div class="page-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <h4>About Us</h4>
        <nav class="sidebar-nav">
            <a href="/philosophy">Educational Philosophy</a>
            <a href="/background">Institutional Background</a>
            <a href="/contact" class="active">Contact Information</a>
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
            <h3 class="content-title">FACEBOOK</h3>
            <a href="https://www.facebook.com/adssi1987" target="_blank" rel="noopener noreferrer" class="btn btn-primary"> Visit Facebook
        </div>


    </section>

    
</div>

<style>

.hero-contact {
    background: linear-gradient(135deg, #001e82, #142c7e);
    color: #fff;
    text-align: center;
    min-height: 60px;   /* set your desired height */
    padding: 50px 20px;  /* optional extra padding */
    display: flex;
    justify-content: center;
    align-items: center;
}
.hero-contact h2 {
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
@endsection
