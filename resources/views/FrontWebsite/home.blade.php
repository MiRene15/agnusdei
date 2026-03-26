@extends('layouts.app')

@section('title', 'Agnus Dei School Systems INC.')

@section('content')
<section class="site-hero">
    <div class="site-hero-inner">
        <p class="site-kicker">Agnus Dei School Systems, Inc.</p>
        <h1>Affordable quality education shaped by character, faith, and future-ready learning</h1>
        <p>From admission to enrollment, the school experience is guided by clear systems, values-based formation, and a commitment to helping learners grow with purpose.</p>
        <div class="hero-actions">
            <a href="{{ route('register') }}" class="hero-primary">Open Student Portal</a>
            <a href="/requirements" class="hero-secondary">View Admission Guide</a>
        </div>
    </div>
</section>

<section class="site-shell">
    <div class="section-band">
        <div>
            <p class="section-kicker">Why Families Choose ADSSI</p>
            <h2>One school journey, built with clarity and care</h2>
        </div>
        <p>The public website reflects the same direction as the system itself: clear admissions, transparent tuition guidance, and organized portal flow for students, parents, and staff.</p>
    </div>

    <div class="grid-three">
        <article class="info-card">
            <p class="card-kicker">Admissions</p>
            <h3>Requirements and procedures made clearer</h3>
            <p>Families can quickly review grade-level requirements, Senior High entry documents, and the enrollment steps before starting an application.</p>
            <a href="/requirements">Explore requirements</a>
        </article>

        <article class="info-card">
            <p class="card-kicker">Tuition Guidance</p>
            <h3>Discounts and payment plans in one place</h3>
            <p>Cash-plan, honors, family, carryover, and payment guidance are easier to understand before meeting the cashier.</p>
            <a href="/discounts">View discounts</a>
        </article>

        <article class="info-card">
            <p class="card-kicker">Academic Path</p>
            <h3>Programs that grow with the learner</h3>
            <p>From early years to Senior High, each stage is organized for stronger continuity, values formation, and better academic alignment.</p>
            <a href="/program-offerings">See program offerings</a>
        </article>
    </div>

    <div class="grid-two">
        <section class="content-card">
            <p class="card-kicker">Learning Focus</p>
            <h2>Faith, formation, and practical readiness</h2>
            <p>The school develops students through academic structure, Christian faith formation, and skills that prepare them for responsible participation in a changing world.</p>
            <ul class="content-list compact">
                <li>Character formation and civic consciousness</li>
                <li>Critical thinking, communication, and collaboration</li>
                <li>Clearer registrar, cashier, and student portal coordination</li>
            </ul>
        </section>

        <section class="content-card">
            <p class="card-kicker">Account Portal</p>
            <h2>Start from the right doorway</h2>
            <div class="portal-stack">
                <a href="{{ route('register') }}">Student Portal</a>
                <a href="{{ route('staff.register') }}">Staff Portal</a>
                <a href="{{ route('login') }}">Login</a>
            </div>
        </section>
    </div>
</section>

<style>
.site-hero { background:
    radial-gradient(circle at 18% 22%, rgba(191, 219, 254, 0.18), transparent 24%),
    radial-gradient(circle at 82% 18%, rgba(96, 165, 250, 0.16), transparent 28%),
    linear-gradient(135deg, #041d5c 0%, #07308b 38%, #0a43b7 72%, #0b4dc9 100%); color: #fff; padding: 82px 24px 88px; }
.site-hero-inner { max-width: 1080px; margin: 0 auto; }
.site-kicker { text-transform: uppercase; letter-spacing: 0.14em; font-size: 12px; opacity: .84; margin-bottom: 14px; }
.site-hero h1 { font-size: clamp(38px, 6vw, 64px); line-height: 1; max-width: 800px; }
.site-hero p { margin-top: 18px; max-width: 740px; color: rgba(255,255,255,.86); font-size: 17px; line-height: 1.8; }
.hero-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 26px; }
.hero-primary, .hero-secondary { display: inline-flex; align-items: center; justify-content: center; padding: 14px 18px; border-radius: 999px; text-decoration: none; font-weight: 600; }
.hero-primary { background: #ffffff; color: #062b8f; }
.hero-secondary { border: 1px solid rgba(255,255,255,.28); color: #fff; background: rgba(255,255,255,.08); }
.site-shell { max-width: 1220px; margin: 0 auto; padding: 34px 22px 44px; display: grid; gap: 20px; }
.section-band, .info-card, .content-card { background: #fff; border: 1px solid #dbeafe; border-radius: 22px; box-shadow: 0 18px 45px rgba(15, 23, 42, .07); }
.section-band { padding: 26px; display: grid; grid-template-columns: minmax(0,1fr) minmax(280px,.9fr); gap: 20px; }
.section-kicker, .card-kicker { text-transform: uppercase; letter-spacing: 0.14em; font-size: 12px; color: #1d4ed8; margin-bottom: 10px; }
.section-band h2, .content-card h2 { font-size: clamp(28px, 4vw, 40px); color: #062b8f; }
.section-band p, .info-card p, .content-card p { color: #334155; line-height: 1.8; }
.grid-three { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 20px; }
.grid-two { display: grid; grid-template-columns: minmax(0,1.1fr) minmax(280px,.9fr); gap: 20px; }
.info-card, .content-card { padding: 26px; }
.info-card h3 { margin: 10px 0 12px; color: #062b8f; font-size: 24px; line-height: 1.2; }
.info-card a { text-decoration: none; color: #1d4ed8; font-weight: 600; }
.content-list { padding-left: 20px; display: grid; gap: 8px; color: #334155; line-height: 1.8; }
.content-list.compact { gap: 6px; }
.portal-stack { display: grid; gap: 12px; margin-top: 18px; }
.portal-stack a { text-decoration: none; padding: 14px 16px; border-radius: 16px; background: #eff6ff; border: 1px solid #bfdbfe; color: #062b8f; font-weight: 600; }
@media (max-width: 980px) { .section-band, .grid-three, .grid-two { grid-template-columns: 1fr; } }
</style>
@endsection

