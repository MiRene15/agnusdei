@extends('layouts.app')

@section('title', 'Institutional Background - Agnus Dei School Systems INC.')

@section('content')

<section class="hero hero-background">
    <h2>Institutional Background</h2>
</section>


<div class="page-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <h4>About Us</h4>
        <nav class="sidebar-nav">
            <a href="/philosophy">Educational Philosophy</a>
            <a href="/background" class="active">Institutional Background</a>
            <a href="/contact">Contact Information</a>
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
            <h3 class="content-title">INSTITUTIONAL BACKGROUND</h3>
            <p>
                The School was incorporated in 1987 as the AGNUS DEI PREPARATORY SCHOOL SYSTEMS, INC., 
                a non-stock educational institution giving free pre-elementary education to sons and 
                daughters of the Agnus Dei Prayer Community. Soon afterwards, it admitted children of 
                non-members.
            </p>
            <br>
            <p>
                The parents were not required to pay any school fees. The salaries of the two teachers 
                were paid out of the personal funds of the founder who, likewise, constructed a two-room 
                school building. Parents volunteered as teacher-aides and as janitors. On their own 
                initiative, they contributed to pay for the utilities and the salary of the security 
                guard. The School was operated as such for a number of years. Enrollment and graduations 
                were meaningful events for the members of the prayer community. The School was issued 
                Recognition Certificate No. 100149 by DepEd Director Garma of Region 4-A.
            </p>
            <br>
            <p>
                The inevitable happened. Parents soon requested that the School offer the full elementary 
                education course. They agreed to the collection of fees. The first batch of graduates of 
                elementary course received their diplomas in March 1977. The School was awarded 
                Recognition Certificate No. 111242.
            </p>
            <br>
            <p>
                The DepEd required the School to amend its original Articles of Incorporation and 
                By-Laws to truly reflect the levels of instruction being offered. The Securities 
                and Exchange Commission issued its Company Registration No. 144863, the Certificate 
                of Filing of Amended Articles of Incorporation and the Certificate of Amended By-Laws 
                on October 28, 2005. The DepEd awarded Recognition Certificate No. 116129 to the School 
                for the complete secondary course (NSEC-BEC). In SY 2005–2006, the School produced its 
                first batch of high school graduates.
            </p>
            <br>
            <p>
                The Board of Trustees, composed of the members of the Rosalinas Family, stands 
                committed to support the School. Over the years, the Trustees had to provide funds, 
                for building and the facilities. In SY 2006–2007, the School became self-supporting 
                as far as operational expenses are concerned. The School site and buildings are on a 
                long-term lease from the founder.
            </p>
            <br>
            <p>
                On April 24, 2015, the School was granted Government Permit (R-IVA) 
                No. SHS-75, s. 2015, a provisional permit to operate the Senior High School 
                Program, offering Academic Track specializing in Accountancy, Business, and 
                Management (ABM), Humanities and Social Sciences (HUMMS), and General Academics (GAs),
                and Technical-Vocational (TechVoc) Track focusing on Computer Programming, 
                Industrial Arts, and Home Economics. At present, the School offers ABM, HUMMS and GAs.
            </p>

        </div>

    </section>

    
</div>

<style>

.hero-background {
    background: linear-gradient(135deg, #001e82, #142c7e);
    color: #fff;
    text-align: center;
    min-height: 60px;   /* set your desired height */
    padding: 50px 20px;  /* optional extra padding */
    display: flex;
    justify-content: center;
    align-items: center;
}
.hero-background h2 {
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
