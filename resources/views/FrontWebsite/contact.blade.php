@extends('layouts.app')

@section('title', 'Contact Information - Agnus Dei School Systems INC.')

@section('content')
<section class="site-hero">
    <div class="site-hero-inner">
        <p class="site-kicker">Contact Information</p>
        <h1>Reach the school through its official public channel</h1>
        <p>For announcements, inquiries, and school updates, families may connect through the official Agnus Dei School Systems, Inc. Facebook presence.</p>
    </div>
</section>

<section class="page-shell">
    <aside class="page-sidebar">
        <div class="nav-card">
            <p class="nav-label">About Us</p>
            <a href="/philosophy">Educational Philosophy</a>
            <a href="/background">Institutional Background</a>
            <a href="/contact" class="active">Contact Information</a>
        </div>
        <div class="side-card">
            <p class="side-title">Best For</p>
            <div class="side-item"><strong>Announcements</strong><span>School updates and public-facing posts</span></div>
            <div class="side-item"><strong>Inquiries</strong><span>General communication and public questions</span></div>
            <div class="side-item"><strong>Portal Reminder</strong><span>Student and staff records stay inside the system</span></div>
        </div>
    </aside>

    <section class="page-main">
        <div class="content-card">
            <p class="card-kicker">Official Channel</p>
            <h3>Facebook Page</h3>
            <p>The official Facebook page is the current public touchpoint for school announcements, updates, and general communication.</p>
            <div class="link-box">
                <span>Official Page</span>
                <strong>facebook.com/adssi1987</strong>
            </div>
            <a href="https://www.facebook.com/adssi1987" target="_blank" rel="noopener noreferrer" class="action-link">Visit Facebook Page</a>
        </div>

        <div class="content-card">
            <p class="card-kicker">Portal Reminder</p>
            <h3>Account access is separate</h3>
            <p>Student and staff accounts are handled through the Account Portal in the main navigation. Public announcements stay on the public-facing channel, while records and transactions remain inside the system.</p>
            <div class="portal-stack">
                <a href="{{ route('register') }}">Student Portal</a>
                <a href="{{ route('staff.register') }}">Staff Portal</a>
            </div>
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
.nav-card, .side-card, .content-card { background: #fff; border: 1px solid #dbeafe; border-radius: 22px; box-shadow: 0 18px 45px rgba(15, 23, 42, .07); }
.nav-card, .side-card, .content-card { padding: 26px; }
.nav-card { display: grid; gap: 12px; }
.nav-label, .side-title, .card-kicker { text-transform: uppercase; letter-spacing: 0.12em; font-size: 12px; color: #1d4ed8; margin-bottom: 10px; }
.nav-card a { text-decoration: none; color: #0f172a; font-weight: 600; padding: 10px 12px; border-radius: 14px; }
.nav-card a.active, .nav-card a:hover { background: #dbeafe; color: #1d4ed8; }
.side-item { display: grid; gap: 4px; padding: 12px 0; border-bottom: 1px solid #dbeafe; }
.side-item:last-child { border-bottom: none; }
.side-item strong { color: #062b8f; }
.side-item span { color: #475569; font-size: 14px; }
.content-card h3 { color: #062b8f; font-size: 24px; margin-bottom: 12px; }
.content-card p { color: #334155; line-height: 1.8; }
.link-box { margin-top: 16px; padding: 16px; border-radius: 18px; background: #eff6ff; border: 1px solid #bfdbfe; }
.link-box span { display: block; margin-bottom: 8px; text-transform: uppercase; letter-spacing: .12em; font-size: 12px; color: #1d4ed8; }
.link-box strong { color: #062b8f; word-break: break-word; }
.action-link { display: inline-flex; margin-top: 18px; text-decoration: none; padding: 14px 18px; border-radius: 999px; background: #062b8f; color: #fff; font-weight: 600; }
.portal-stack { display: grid; gap: 12px; margin-top: 18px; }
.portal-stack a { text-decoration: none; padding: 14px 16px; border-radius: 16px; background: #eff6ff; border: 1px solid #bfdbfe; color: #062b8f; font-weight: 600; }
@media (max-width: 980px) { .page-shell { grid-template-columns: 1fr; } }
</style>
@endsection

