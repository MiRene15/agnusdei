@extends('layouts.app')

@section('title', 'Educational Philosophy - Agnus Dei School Systems INC.')

@section('content')
<section class="page-hero">
    <div class="page-hero-inner">
        <p class="page-kicker">Educational Philosophy</p>
        <h1>Formation, integrity, and future-ready learning guided by faith</h1>
        <p>The school vision, mission, goals, and values work together to form learners who are intellectually prepared, spiritually grounded, and socially responsible.</p>
    </div>
</section>

<section class="page-shell">
    <aside class="page-sidebar">
        <div class="nav-card">
            <p class="nav-label">About Us</p>
            <a href="/philosophy" class="active">Educational Philosophy</a>
            <a href="/background">Institutional Background</a>
            <a href="/contact">Contact Information</a>
        </div>
        <div class="summary-card">
            <p class="summary-title">Focus Areas</p>
            <div class="summary-item"><strong>Character</strong><span>Faith and integrity in daily formation</span></div>
            <div class="summary-item"><strong>Readiness</strong><span>21st century skills and responsible action</span></div>
            <div class="summary-item"><strong>Purpose</strong><span>Learning that serves God, community, and country</span></div>
        </div>
    </aside>

    <section class="page-main">
        <div class="lead-card">
            <p>ADSSI believes education must shape the whole learner. Academic knowledge, moral awareness, spiritual growth, discipline, and practical readiness are treated as part of one meaningful formation journey.</p>
        </div>

        <div class="content-card">
            <h3>School Vision</h3>
            <p>Capacitating the youth to develop the 21st Century skills with the fusion of character formation and intellectual integrity to meet the challenges ahead.</p>
        </div>

        <div class="content-card">
            <h3>School Mission</h3>
            <p>The School commits its resources, time, and best efforts of the Administration, faculty, and staff to provide affordable good quality education, to develop strong Christian faith, and to make the curriculum instructions timelier and more relevant in order to deepen the civic and spiritual consciousness of every learner.</p>
        </div>

        <div class="content-card">
            <h3>Goals and Objectives</h3>
            <ol class="content-list">
                <li>To provide the basic knowledge and foundation in developing the cognitive, affective, and psychomotor skills, attitudes, and values, including their moral and spiritual dimensions essential to the child's personal development and necessary for living and contributing to a developing and changing social environment.</li>
                <li>To provide the learning experiences that enhance the child's awareness of and responsiveness to the changes in society and to prepare for constructive and effective involvement.</li>
                <li>To promote and intensify the child's knowledge of identification with and love for country and people to which he belongs.</li>
                <li>To develop the child's knowledge, love, and care for the environment.</li>
                <li>To develop awareness of the interconnectivity of peoples around the world and encourage tolerance of cultural diversity and service to the wider world.</li>
                <li>To promote work experiences which develop and enhance orientation to the world of work and creativity in order to prepare the learner to engage in honest and gainful work.</li>
                <li>To enhance mastery in the use of tools and technology and aptitude to innovate as a means of increasing productivity.</li>
                <li>To develop different aptitudes, interests, and skills to prepare the learner for real work and further formal studies.</li>
                <li>To help the child realize that in the pursuit of education, he seeks to glorify and serve God.</li>
                <li>To contribute to the evangelization efforts of the Church.</li>
            </ol>
        </div>

        <div class="split-grid">
            <div class="content-card">
                <h3>Core Values</h3>
                <ul class="content-list compact">
                    <li>R - Resiliency and Adaptability</li>
                    <li>I - Industry and Integrity</li>
                    <li>S - Social Transformation</li>
                    <li>E - Empathy and Emotional Intelligence</li>
                    <li>U - Upskilling and Reskilling</li>
                    <li>P - Proactive Orientation</li>
                </ul>
            </div>

            <div class="content-card">
                <h3>21st Century Skills</h3>
                <ul class="content-list compact">
                    <li>Critical Thinking</li>
                    <li>Collaboration</li>
                    <li>Communication</li>
                    <li>Creativity</li>
                    <li>Concern for the Environment</li>
                    <li>Computing ICT Literacy</li>
                    <li>Career and Learning Self-Reliance</li>
                    <li>Cross Cultural Understanding</li>
                </ul>
            </div>
        </div>
    </section>
</section>

<style>
.page-hero { background:
    radial-gradient(circle at 18% 22%, rgba(191, 219, 254, 0.18), transparent 24%),
    radial-gradient(circle at 82% 18%, rgba(96, 165, 250, 0.16), transparent 28%),
    linear-gradient(135deg, #041d5c 0%, #07308b 38%, #0a43b7 72%, #0b4dc9 100%); color: #fff; padding: 78px 24px 84px; }
.page-hero-inner { max-width: 1040px; margin: 0 auto; }
.page-kicker { text-transform: uppercase; letter-spacing: 0.14em; font-size: 12px; opacity: .84; margin-bottom: 14px; }
.page-hero h1 { font-size: clamp(34px, 6vw, 58px); line-height: 1.02; max-width: 760px; }
.page-hero p { margin-top: 18px; max-width: 720px; color: rgba(255,255,255,.86); font-size: 17px; line-height: 1.8; }
.page-shell { max-width: 1220px; margin: 0 auto; padding: 34px 22px 44px; display: grid; grid-template-columns: 280px minmax(0, 1fr); gap: 24px; }
.page-sidebar, .page-main { display: grid; gap: 20px; align-content: start; }
.nav-card, .summary-card, .lead-card, .content-card { background: #fff; border: 1px solid #dbeafe; border-radius: 22px; box-shadow: 0 18px 45px rgba(15, 23, 42, .07); }
.nav-card { padding: 22px; display: grid; gap: 12px; }
.nav-label, .summary-title { text-transform: uppercase; letter-spacing: 0.12em; font-size: 12px; color: #64748b; }
.nav-card a { text-decoration: none; color: #0f172a; font-weight: 600; padding: 10px 12px; border-radius: 14px; }
.nav-card a.active, .nav-card a:hover { background: #dbeafe; color: #1d4ed8; }
.summary-card { padding: 22px; }
.summary-item { display: grid; gap: 4px; padding: 12px 0; border-bottom: 1px solid #dbeafe; }
.summary-item:last-child { border-bottom: none; }
.summary-item strong { color: #062b8f; }
.summary-item span { color: #475569; font-size: 14px; }
.lead-card, .content-card { padding: 26px; }
.lead-card p { color: #334155; font-size: 17px; line-height: 1.9; }
.content-card h3 { color: #062b8f; font-size: 24px; margin-bottom: 14px; }
.content-card p, .content-list { color: #334155; font-size: 15px; line-height: 1.8; }
.content-list { padding-left: 20px; display: grid; gap: 8px; }
.content-list.compact { gap: 6px; }
.split-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
@media (max-width: 980px) { .page-shell, .split-grid { grid-template-columns: 1fr; } }
</style>
@endsection

