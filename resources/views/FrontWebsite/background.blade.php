@extends('layouts.app')

@section('title', 'Institutional Background - Agnus Dei School Systems INC.')

@section('content')
<section class="site-hero">
    <div class="site-hero-inner">
        <p class="site-kicker">Institutional Background</p>
        <h1>Built through faith, service, and steady growth since 1987</h1>
        <p>Agnus Dei School Systems, Inc. grew from a community-rooted learning space into a full basic education institution committed to affordable quality education, strong values, and future-ready learners.</p>
    </div>
</section>

<section class="page-shell">
    <aside class="page-sidebar">
        <div class="nav-card">
            <p class="nav-label">About Us</p>
            <a href="/philosophy">Educational Philosophy</a>
            <a href="/background" class="active">Institutional Background</a>
            <a href="/contact">Contact Information</a>
        </div>
        <div class="side-card">
            <p class="side-title">Legacy Snapshot</p>
            <div class="side-item"><strong>1987</strong><span>School foundation</span></div>
            <div class="side-item"><strong>2005-2006</strong><span>First full high school graduates</span></div>
            <div class="side-item"><strong>2015</strong><span>Senior High permit granted</span></div>
        </div>
    </aside>

    <section class="page-main">
        <div class="lead-card">
            <p>The School began as Agnus Dei Preparatory School Systems, Inc., a non-stock educational institution created to serve the children of the Agnus Dei Prayer Community. It first offered free pre-elementary education and later opened its doors more widely as families asked for a fuller academic journey.</p>
        </div>

        <div class="grid-two">
            <article class="content-card">
                <p class="card-kicker">1987</p>
                <h3>Community-rooted beginnings</h3>
                <p>The school started with a mission-centered approach to formation, supported by committed families, volunteer service, and a strong sense of stewardship.</p>
            </article>

            <article class="content-card">
                <p class="card-kicker">Elementary Expansion</p>
                <h3>Growing with learner needs</h3>
                <p>As demand increased, the school expanded from pre-elementary to a full elementary offering, giving students a more continuous academic path.</p>
            </article>

            <article class="content-card">
                <p class="card-kicker">2005-2006</p>
                <h3>Complete secondary program</h3>
                <p>The institution reached a major milestone with full secondary recognition and produced its first batch of high school graduates during school year 2005-2006.</p>
            </article>

            <article class="content-card">
                <p class="card-kicker">2006-2007</p>
                <h3>Operational stability</h3>
                <p>By school year 2006-2007, the school had become self-supporting in day-to-day operations while continuing to receive guidance and support from its trustees.</p>
            </article>
        </div>

        <div class="section-band">
            <div>
                <p class="card-kicker">Senior High Milestone</p>
                <h2>2015 opened a new chapter</h2>
            </div>
            <p>On April 24, 2015, the school was granted permit to operate Senior High School, extending its mission into more specialized and future-facing learning paths while keeping faith and character formation at the center.</p>
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
.site-hero h1 { font-size: clamp(34px, 6vw, 58px); line-height: 1.02; max-width: 760px; }
.site-hero p { margin-top: 18px; max-width: 720px; color: rgba(255,255,255,.86); font-size: 17px; line-height: 1.8; }
.page-shell { max-width: 1220px; margin: 0 auto; padding: 34px 22px 44px; display: grid; grid-template-columns: 280px minmax(0, 1fr); gap: 24px; }
.page-sidebar, .page-main { display: grid; gap: 20px; align-content: start; }
.nav-card, .side-card, .lead-card, .content-card, .section-band { background: #fff; border: 1px solid #dbeafe; border-radius: 22px; box-shadow: 0 18px 45px rgba(15, 23, 42, .07); }
.nav-card, .side-card, .lead-card, .content-card, .section-band { padding: 26px; }
.nav-label, .side-title, .card-kicker { text-transform: uppercase; letter-spacing: 0.12em; font-size: 12px; color: #1d4ed8; margin-bottom: 10px; }
.nav-card { display: grid; gap: 12px; }
.nav-card a { text-decoration: none; color: #0f172a; font-weight: 600; padding: 10px 12px; border-radius: 14px; }
.nav-card a.active, .nav-card a:hover { background: #dbeafe; color: #1d4ed8; }
.side-item { display: grid; gap: 4px; padding: 12px 0; border-bottom: 1px solid #dbeafe; }
.side-item:last-child { border-bottom: none; }
.side-item strong { color: #062b8f; font-size: 20px; }
.side-item span { color: #475569; font-size: 14px; }
.lead-card p, .content-card p, .section-band p { color: #334155; line-height: 1.8; }
.lead-card p { font-size: 17px; line-height: 1.9; }
.content-card h3 { color: #062b8f; font-size: 24px; margin-bottom: 12px; }
.section-band { display: grid; grid-template-columns: minmax(0,1fr) minmax(280px,.9fr); gap: 20px; }
.section-band h2 { color: #062b8f; font-size: clamp(28px, 4vw, 40px); }
.grid-two { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 20px; }
@media (max-width: 980px) { .page-shell, .grid-two, .section-band { grid-template-columns: 1fr; } }
</style>
@endsection

