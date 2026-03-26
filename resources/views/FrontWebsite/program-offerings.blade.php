@extends('layouts.app')

@section('title', 'Program Offerings - Agnus Dei School Systems INC.')

@section('content')
<section class="site-hero">
    <div class="site-hero-inner">
        <p class="site-kicker">Program Offerings</p>
        <h1>Learning paths that grow with every stage of the learner journey</h1>
        <p>From foundational early years to Senior High specialization, the school offers a guided academic path shaped by values, structure, and learner readiness.</p>
    </div>
</section>

<section class="page-shell">
    <aside class="page-sidebar">
        <div class="nav-card">
            <p class="nav-label">Admissions</p>
            <a href="/program-offerings" class="active">Program Offerings</a>
            <a href="/requirements">Requirements and Procedures</a>
            <a href="/discounts">Discounts and Privileges</a>
        </div>
        <div class="side-card">
            <p class="side-title">What Families Can Expect</p>
            <div class="side-item"><strong>Progression</strong><span>Structured movement from basic to advanced levels</span></div>
            <div class="side-item"><strong>Alignment</strong><span>Track-based section, subject, and schedule matching</span></div>
            <div class="side-item"><strong>Formation</strong><span>Programs supported by values education and discipline</span></div>
        </div>
    </aside>

    <section class="page-main">
        <div class="lead-card">
            <p>Our academic offerings are organized to help students move through each level with clarity, continuity, and preparation for the next stage. Senior High track selection also supports cleaner linking of student schedules, subjects, and teacher assignments in the system.</p>
        </div>

        <div class="grid-two">
            <article class="content-card">
                <p class="card-kicker">Foundation Years</p>
                <h3>Nursery and Kinder</h3>
                <p>Early learning programs support readiness skills, guided routines, language growth, social development, and faith-centered formation.</p>
                <ul class="content-list compact"><li>Nursery</li><li>Kinder</li></ul>
            </article>

            <article class="content-card">
                <p class="card-kicker">Elementary</p>
                <h3>Grade 1 to Grade 6</h3>
                <p>Elementary students build academic foundations in literacy, numeracy, values, and core school competencies needed for long-term progress.</p>
                <ul class="content-list compact"><li>Grade 1</li><li>Grade 2</li><li>Grade 3</li><li>Grade 4</li><li>Grade 5</li><li>Grade 6</li></ul>
            </article>

            <article class="content-card">
                <p class="card-kicker">Junior High</p>
                <h3>Grade 7 to Grade 10</h3>
                <p>Junior High strengthens discipline, academic maturity, and readiness for advanced coursework while supporting learner identity and responsibility.</p>
                <ul class="content-list compact"><li>Grade 7</li><li>Grade 8</li><li>Grade 9</li><li>Grade 10</li></ul>
            </article>

            <article class="content-card">
                <p class="card-kicker">Senior High</p>
                <h3>Grade 11 to Grade 12</h3>
                <p>Senior High students select a track during admission so that sections, subjects, and teacher assignments can be aligned more accurately in the school system.</p>
                <div class="pill-row"><span>STEM</span><span>ABM</span><span>HUMSS</span><span>GAS</span></div>
            </article>
        </div>

        <div class="section-band">
            <div>
                <p class="card-kicker">System Alignment</p>
                <h2>Senior High track selection supports cleaner academic linking</h2>
            </div>
            <p>The portal captures the Senior High track during admission so the registrar can align sections, teachers, and subjects more accurately and reduce schedule mismatches.</p>
        </div>
    </section>
</section>

<style>
.site-hero { background:
    radial-gradient(circle at 18% 22%, rgba(191, 219, 254, 0.18), transparent 24%),
    radial-gradient(circle at 82% 18%, rgba(96, 165, 250, 0.16), transparent 28%),
    linear-gradient(135deg, #041d5c 0%, #07308b 38%, #0a43b7 72%, #0b4dc9 100%); color: #fff; padding: 78px 24px 84px; }
.site-hero-inner { max-width: 1040px; margin: 0 auto; }
.site-kicker { text-transform: uppercase; letter-spacing: 0.14em; font-size: 12px; opacity: .84; margin-bottom: 14px; }
.site-hero h1 { font-size: clamp(34px, 6vw, 58px); line-height: 1.02; max-width: 820px; }
.site-hero p { margin-top: 18px; max-width: 720px; color: rgba(255,255,255,.86); font-size: 17px; line-height: 1.8; }
.page-shell { max-width: 1220px; margin: 0 auto; padding: 34px 22px 44px; display: grid; grid-template-columns: 280px minmax(0, 1fr); gap: 24px; }
.page-sidebar, .page-main { display: grid; gap: 20px; align-content: start; }
.nav-card, .side-card, .lead-card, .content-card, .section-band { background: #fff; border: 1px solid #dbeafe; border-radius: 22px; box-shadow: 0 18px 45px rgba(15, 23, 42, .07); }
.nav-card, .side-card, .lead-card, .content-card, .section-band { padding: 26px; }
.nav-card { display: grid; gap: 12px; }
.nav-label, .side-title, .card-kicker { text-transform: uppercase; letter-spacing: 0.12em; font-size: 12px; color: #1d4ed8; margin-bottom: 10px; }
.nav-card a { text-decoration: none; color: #0f172a; font-weight: 600; padding: 10px 12px; border-radius: 14px; }
.nav-card a.active, .nav-card a:hover { background: #dbeafe; color: #1d4ed8; }
.side-item { display: grid; gap: 4px; padding: 12px 0; border-bottom: 1px solid #dbeafe; }
.side-item:last-child { border-bottom: none; }
.side-item strong { color: #062b8f; }
.side-item span { color: #475569; font-size: 14px; }
.lead-card p, .content-card p, .section-band p { color: #334155; line-height: 1.8; }
.lead-card p { font-size: 17px; line-height: 1.9; }
.content-card h3 { color: #062b8f; font-size: 24px; margin-bottom: 12px; }
.content-list { padding-left: 20px; display: grid; gap: 8px; color: #334155; line-height: 1.8; }
.content-list.compact { gap: 6px; }
.pill-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 16px; }
.pill-row span { padding: 10px 14px; border-radius: 999px; background: #eff6ff; border: 1px solid #bfdbfe; color: #062b8f; font-weight: 600; font-size: 14px; }
.section-band { display: grid; grid-template-columns: minmax(0,1fr) minmax(280px,.9fr); gap: 20px; }
.section-band h2 { color: #062b8f; font-size: clamp(28px, 4vw, 40px); }
.grid-two { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 20px; }
@media (max-width: 980px) { .page-shell, .grid-two, .section-band { grid-template-columns: 1fr; } }
</style>
@endsection

