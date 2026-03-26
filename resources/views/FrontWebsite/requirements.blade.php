@extends('layouts.app')

@section('title', 'Requirements and Procedures - Agnus Dei School Systems INC.')

@section('content')
<section class="page-hero">
    <div class="page-hero-inner">
        <p class="page-kicker">Requirements and Procedures</p>
        <h1>Admission information arranged in a clearer and more welcoming school guide</h1>
        <p>Families, transferees, and returning students can review requirements, SHS entry rules, procedures, and key financial reminders in one polished admission page.</p>
    </div>
</section>

<section class="page-shell">
    <aside class="page-sidebar">
        <div class="nav-card">
            <p class="nav-label">Admissions</p>
            <a href="/program-offerings">Program Offerings</a>
            <a href="/requirements" class="active">Requirements and Procedures</a>
            <a href="/discounts">Discounts and Privileges</a>
        </div>
        <div class="side-card">
            <p class="side-title">Quick Notes</p>
            <div class="side-item"><strong>SHS Track</strong><span>Selected during admission for cleaner subject and schedule matching</span></div>
            <div class="side-item"><strong>Voucher Review</strong><span>Registrar and cashier confirmation are both needed</span></div>
            <div class="side-item"><strong>Monthly Due</strong><span>Expected every 15th of the month</span></div>
        </div>
    </aside>

    <section class="page-main">
        <div class="lead-card">
            <p>The admission process is designed to help families prepare requirements early, complete the needed review steps, and understand how payment, sectioning, and portal access connect to the full enrollment flow.</p>
        </div>

        <div class="hero-band">
            <div>
                <p class="band-kicker">Admission Readiness</p>
                <h2>Prepare documents early and move through the process with less friction</h2>
            </div>
            <p>The school workflow is most effective when requirements are complete, identity details are accurate, and the student record is aligned early for billing, sectioning, and subject assignment.</p>
        </div>

        <div class="content-card wide">
            <h3>Admission Policies and Requirements</h3>
            <p><strong>New Students</strong> from Kinder to Senior High School must prepare the appropriate supporting documents before the admission review begins.</p>
            <ul class="content-list">
                <li>Kinder 1: 4 years old by the opening of the school year.</li>
                <li>Kinder 2: 5 years old by the opening of the school year.</li>
                <li>Grade 1: 6 years old by the opening of the school year.</li>
                <li>Certification from the kindergarten school last attended for entering Grade 1.</li>
                <li>Certified true copy of the latest Form 138.</li>
                <li>PSA-authenticated birth certificate for new enrollees.</li>
                <li>Certificate of Good Moral Character signed by the principal of the previous school.</li>
                <li>Two 2x2 colored ID pictures with white background.</li>
                <li>Accomplished student data form and parent data form.</li>
                <li>Incoming JHS transferees may be interviewed by the department coordinator or school head.</li>
            </ul>
        </div>

        <div class="grid-two">
            <div class="content-card tone-a">
                <p class="card-kicker">Senior High Entry</p>
                <h3>Additional Requirements for SHS Entrants</h3>
                <ul class="content-list compact">
                    <li>Graduates from ESC-certified private schools: ESC Grant Certificate.</li>
                    <li>Graduates from non-ESC-certified private schools: SHS Voucher.</li>
                    <li>Senior High applicants choose their track during admission.</li>
                    <li>Voucher submission is reviewed by the registrar and cashier before it can count toward access or payment readiness.</li>
                </ul>
            </div>

            <div class="content-card tone-b">
                <p class="card-kicker">Returning Students</p>
                <h3>Old Students and Readmission</h3>
                <ul class="content-list compact">
                    <li>Form 138 or report card.</li>
                </ul>
                <p style="margin-top:14px;">Only students with no failing marks and a general average of 75 percent or higher are eligible for readmission to the next year level, unless readmitted under approved academic probation conditions.</p>
            </div>
        </div>

        <div class="content-card wide">
            <h3>Admission Procedure</h3>
            <ol class="content-list">
                <li>Fill out the application form and submit all essential documents and requirements.</li>
                <li>New enrollees and transferees undergo admission interview and initial assessment.</li>
                <li>Pay the necessary fees or complete the approved SHS voucher verification flow to be officially enrolled.</li>
                <li>Attend the orientation activity before the opening of classes.</li>
            </ol>
        </div>

        <div class="content-card wide">
            <h3>Important Financial Policies</h3>
            <ul class="content-list">
                <li>Once a student is enrolled, the school reserves a slot for the whole school year.</li>
                <li>Withdrawal after the fourth week of classes generally results in the full school-year tuition being charged unless there is a justifiable reason.</li>
                <li>Registration and reservation fees are non-refundable.</li>
                <li>Monthly dues are expected on the 15th of the month.</li>
                <li>Two successive unpaid monthly dues may prevent a student from taking required tests unless an approved arrangement is made.</li>
                <li>Back accounts block examinations and release of school credentials.</li>
                <li>Approved leftover tuition balances may only be carried to the next school year upon school approval and compliance review.</li>
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
