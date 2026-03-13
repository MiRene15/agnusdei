@extends('layouts.app')

@section('title', 'Educational Philosophy - Agnus Dei School Systems INC.')

@section('content')

<section class="hero hero-philosophy">
    <h2>Educational Philosophy</h2>
</section>


<div class="page-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <h4>About Us</h4>
        <nav class="sidebar-nav">
            <a href="/philosophy" class="active">Educational Philosophy</a>
            <a href="/background">Institutional Background</a>
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
            <h3 class="content-title">PHILOSOPHY</h3>
            <p>
                An integrated catholic formation through effective and meaningful mentoring, parenting,
                and the personal responsibility of the learner operating in the context of reverence, unity,
                and love ensure success of every child
            </p>
        </div>

        <div class="content-section">
            <h3 class="content-title">VISION</h3>
            <p>
                Capacitating the youth develop the 21st century skills with fusion of character formation
                and intellectual integrity to meet the challenges ahead.
            </p>
        </div>

        <div class="content-section">
            <h3 class="content-title">MISSION</h3>
            <p>
                Agnus Dei School Systems, Inc. commits its resources, time and best efforts of the administration,
                faculty, and staff to provide affordable world-class quality education, to develop strong Christian
                faith, and to make curriculum instructions more timely and relevant to deepen the civic and spiritual
                consciousness of the youth.
            </p>
        </div>

        <div class="content-section">
            <h3 class="content-title">GOALS AND OBJECTIVES</h3>
            <p>These are the goals and objectives of the institution: </p>
                <ol style="padding-left: 40px">
                    <li>Uphold moral righteousness and propriety to strengthen and solidify the character of the youth.</li>
                    <li>Understand fully the meaning of life and make improvements through education.</li>
                    <li>Embrace the uniqueness of every individual to maintain harmonious and strong human relations.</li>
                    <li>Develop a positive attitude and aptitude for work.</li>
                    <li>Understand and develop the basic morals and spiritual values, including the virtues of simplicity, humility,
                        commitment, and patriotism.
                    </li>
                    <li>Develop effective study habits and good command of the basic mathematical processes and critical thinking.</li>
                </ol>
        </div> 

        <div class="content-section">
            <h3 class="content-title">CORE VALUES</h3>
                <ul style="padding-left: 40px">
                    <li>Integrity and Ethics</li>
                    <li>Academic Excellence</li>
                    <li>Unity</li>
                    <li>Industry</li>
                    <li>Respect and Discipline</li>
                    <li>Christian Discipleship</li>
                    <li>Social Transformation</li>
                </ul>
        </div> 

        <div class="content-section">
            <h3 class="content-title">21ST CENTURY SKILLS</h3>
                <ul style="padding-left: 40px">
                    <li>Critical Thinking</li>
                    <li>Creativity</li>
                    <li>Collaboration</li>
                    <li>Cross-cultural Understanding</li>
                    <li>Communication</li>
                    <li>Computing ICT Literacy</li>
                    <li>Career and Learning Self-Reliance</li>
                </ul>
        </div>

    </section>

    
</div>

<style>

.hero-philosophy {
    background: linear-gradient(135deg, #001e82, #142c7e);
    color: #fff;
    text-align: center;
    min-height: 60px;   /* set your desired height */
    padding: 50px 20px;  /* optional extra padding */
    display: flex;
    justify-content: center;
    align-items: center;
}
.hero-philosophy h2 {
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
