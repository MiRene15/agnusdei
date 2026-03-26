@extends('layouts.app')

@section('title', 'Discounts and Privileges - Agnus Dei School Systems INC.')

@section('content')
<section class="page-hero">
    <div class="page-hero-inner">
        <p class="page-kicker">Discounts and Privileges</p>
        <h1>Tuition discounts and payment plans presented with more clarity and warmth</h1>
        <p>Families can review the payment plans, honors discounts, family discounts, grant notes, and balance reminders in a cleaner page that still feels more alive and intentional.</p>
    </div>
</section>

<section class="page-shell">
    <aside class="page-sidebar">
        <div class="nav-card">
            <p class="nav-label">Admissions</p>
            <a href="/program-offerings">Program Offerings</a>
            <a href="/requirements">Requirements and Procedures</a>
            <a href="/discounts" class="active">Discounts and Privileges</a>
        </div>
        <div class="side-card">
            <p class="side-title">Quick Discount Notes</p>
            <div class="side-item"><strong>Cash Plan</strong><span>Discount applies only to full payment upon enrollment</span></div>
            <div class="side-item"><strong>Automatic Rules</strong><span>Honors and family discounts are system-computed when eligible</span></div>
            <div class="side-item"><strong>No Plurality</strong><span>The highest single tuition discount is the only one kept</span></div>
        </div>
    </aside>

    <section class="page-main">
        <div class="lead-card">
            <p>The system now supports automatic honors, family, and cash-plan discount logic while still following the school rule that a student may enjoy only one privilege at a time.</p>
        </div>

        <div class="hero-band">
            <div>
                <p class="band-kicker">Billing Guidance</p>
                <h2>Choose the right payment route before enrollment is finalized</h2>
            </div>
            <p>Cash, monthly, and alternative plans each behave differently in the system, and discount eligibility is calculated around the actual payment setup and verified student record.</p>
        </div>

        <div class="grid-two">
            <div class="content-card tone-a">
                <p class="card-kicker">Payment Plans</p>
                <h3>Schedule of Payments</h3>
                <ul class="content-list compact">
                    <li><strong>Plan A - Cash:</strong> Full payment upon enrollment with a 10 percent tuition discount.</li>
                    <li><strong>Plan B - Monthly:</strong> Minimum down payment of PHP 1,500 upon enrollment, with the remaining fees divided across the school year.</li>
                    <li><strong>Plan C - Alternative:</strong> A custom payment arrangement agreed upon by the parent, cashier, and registrar.</li>
                    <li>Monthly dues are set for the 15th of the month in the system.</li>
                </ul>
            </div>

            <div class="content-card tone-b">
                <p class="card-kicker">System Rules</p>
                <h3>System Discount Logic</h3>
                <ul class="content-list compact">
                    <li>Cash-plan discount is applied only when the full discounted balance is paid upon enrollment.</li>
                    <li>Honors and family discounts are computed automatically from the student record when eligible.</li>
                    <li>No student will enjoy multiple privileges at the same time.</li>
                    <li>The system keeps the single highest tuition discount when multiple rules qualify.</li>
                </ul>
            </div>
        </div>

        <div class="content-card wide">
            <h3>Honors Program Discounts</h3>
            <ul class="content-list">
                <li>Kinder 2 to Grade 6: 1st rank gets 100 percent tuition discount, 2nd rank gets 50 percent, and 3rd rank gets 25 percent.</li>
                <li>Grades 7 to 10: 1st rank gets 100 percent tuition discount, 2nd rank gets 75 percent, and 3rd rank gets 50 percent.</li>
                <li>Public elementary graduates ranked 1st or 2nd overall receive a 100 percent tuition discount when enrolling in Grade 7 at ADSS.</li>
            </ul>
        </div>

        <div class="grid-two">
            <div class="content-card tone-a">
                <p class="card-kicker">Other Discounts</p>
                <h3>Family and Full-Payment Privileges</h3>
                <ul class="content-list compact">
                    <li>Second child family discount: 10 percent on tuition.</li>
                    <li>Third child family discount: 15 percent on tuition.</li>
                    <li>Full payment upon enrollment: 10 percent tuition discount.</li>
                    <li>Discounts apply to tuition fees only.</li>
                    <li>Scholarships and privileges are non-transferable.</li>
                </ul>
            </div>

            <div class="content-card tone-b">
                <p class="card-kicker">Grant Notes</p>
                <h3>ESC Grant and SHS Voucher Notes</h3>
                <ul class="content-list compact">
                    <li>Incoming Grade 7 ESC grantees may qualify for PHP 9,000 based on PEAC criteria.</li>
                    <li>ESC releases are not applied at the beginning of the school term unless officially received.</li>
                    <li>Senior High voucher submissions are reviewed by both the registrar and cashier before voucher credit is applied.</li>
                </ul>
            </div>
        </div>

        <div class="content-card wide">
            <h3>Refund and Balance Rules</h3>
            <ul class="content-list">
                <li>Fees other than tuition are non-refundable.</li>
                <li>Plan A tuition refunds follow the school refund schedule and timing of withdrawal.</li>
                <li>There are no refunds under Plan B.</li>
                <li>Approved remaining balances may only move to the next school year after school approval and compliance review.</li>
            </ul>
        </div>
    </section>
</section>

<style>
.page-hero {
    background:
        radial-gradient(circle at 18% 22%, rgba(191, 219, 254, 0.18), transparent 24%),
        radial-gradient(circle at 82% 18%, rgba(96, 165, 250, 0.16), transparent 28%),
        linear-gradient(135deg, #041d5c 0%, #07308b 38%, #0a43b7 72%, #0b4dc9 100%);
    color: #fff;
    padding: 78px 24px 84px;
}
.page-hero-inner { max-width: 1040px; margin: 0 auto; }
.page-kicker { text-transform: uppercase; letter-spacing: 0.14em; font-size: 12px; opacity: .84; margin-bottom: 14px; }
.page-hero h1 { font-size: clamp(34px, 6vw, 58px); line-height: 1.02; max-width: 820px; }
.page-hero p { margin-top: 18px; max-width: 760px; color: rgba(255,255,255,.86); font-size: 17px; line-height: 1.8; }
.page-shell { max-width: 1220px; margin: 0 auto; padding: 34px 22px 44px; display: grid; grid-template-columns: 280px minmax(0, 1fr); gap: 24px; }
.page-sidebar, .page-main { display: grid; gap: 20px; align-content: start; }
.nav-card, .side-card, .lead-card, .hero-band, .content-card { background: #fff; border: 1px solid #dbeafe; border-radius: 22px; box-shadow: 0 18px 45px rgba(15, 23, 42, .07); }
.nav-card, .side-card, .lead-card, .hero-band, .content-card { padding: 26px; }
.nav-card { display: grid; gap: 12px; }
.nav-label, .side-title, .card-kicker, .band-kicker { text-transform: uppercase; letter-spacing: 0.12em; font-size: 12px; color: #1d4ed8; margin-bottom: 10px; }
.nav-card a { text-decoration: none; color: #0f172a; font-weight: 600; padding: 10px 12px; border-radius: 14px; }
.nav-card a.active, .nav-card a:hover { background: #dbeafe; color: #1d4ed8; }
.side-item { display: grid; gap: 4px; padding: 12px 0; border-bottom: 1px solid #dbeafe; }
.side-item:last-child { border-bottom: none; }
.side-item strong { color: #062b8f; }
.side-item span { color: #475569; font-size: 14px; }
.lead-card p, .content-card p, .hero-band p { color: #334155; line-height: 1.8; }
.lead-card p { font-size: 17px; line-height: 1.9; }
.hero-band { display: grid; grid-template-columns: minmax(0,1fr) minmax(280px,.95fr); gap: 22px; background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%); }
.hero-band h2 { color: #062b8f; font-size: clamp(28px, 4vw, 40px); }
.content-card h3 { color: #062b8f; font-size: 24px; margin-bottom: 14px; }
.content-list { padding-left: 20px; display: grid; gap: 8px; color: #334155; line-height: 1.8; }
.content-list.compact { gap: 6px; }
.grid-two { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
.tone-a { background: linear-gradient(180deg, #f7fbff 0%, #ffffff 100%); }
.tone-b { background: linear-gradient(180deg, #f3f8ff 0%, #ffffff 100%); }
@media (max-width: 980px) { .page-shell, .grid-two, .hero-band { grid-template-columns: 1fr; } }
</style>
@endsection
