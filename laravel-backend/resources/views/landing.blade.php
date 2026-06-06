<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أنيس — شبكة مساحات المذاكرة للطلاب في مصر</title>
    <meta name="description" content="أنيس: اشتراك واحد يمنحك الدخول إلى أي مساحة عمل ومذاكرة شريكة في مصر، مع جلسات مذاكرة حية ومطابقة لشركاء المذاكرة.">
    <meta property="og:title" content="أنيس — شبكة مساحات المذاكرة للطلاب">
    <meta property="og:description" content="اشتراك واحد، كل مساحات المذاكرة قريبة منك.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root{
            --green:#1f9d63; --green-dark:#11603b; --green-soft:#e8f7ef;
            --gold:#f4b740; --ink:#10241a; --muted:#5b6b62;
            --cream:#faf6ee; --bg:#f6faf7; --white:#fff;
            --radius:20px; --shadow:0 24px 60px rgba(17,96,59,.14);
        }
        *{margin:0;padding:0;box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{font-family:'Tajawal',sans-serif;color:var(--ink);background:var(--bg);line-height:1.7;overflow-x:hidden}
        a{text-decoration:none;color:inherit}
        img{max-width:100%;display:block}
        .container{max-width:1160px;margin:0 auto;padding:0 22px}
        section{padding:80px 0}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:14px 28px;border-radius:999px;font-weight:700;font-size:16px;transition:.2s;border:none;cursor:pointer;font-family:inherit}
        .btn-primary{background:var(--green);color:#fff;box-shadow:0 10px 24px rgba(31,157,99,.32)}
        .btn-primary:hover{background:var(--green-dark);transform:translateY(-2px)}

        /* reveal animation */
        .reveal{opacity:0;transform:translateY(26px);transition:.7s cubic-bezier(.2,.7,.2,1)}
        .reveal.in{opacity:1;transform:none}

        /* announcement */
        .topbar{background:var(--green-dark);color:#dff3e8;text-align:center;font-size:14px;padding:9px 16px;font-weight:500}
        .topbar b{color:var(--gold)}

        /* Nav */
        header.nav{position:sticky;top:0;z-index:60;background:rgba(246,250,247,.86);backdrop-filter:blur(12px);border-bottom:1px solid #e4efe8}
        .nav-inner{display:flex;align-items:center;justify-content:space-between;height:74px}
        .brand{display:flex;align-items:center;gap:11px;font-weight:900;font-size:25px;color:var(--green-dark)}
        .brand .mark{width:44px;height:44px;border-radius:13px;background:linear-gradient(135deg,var(--green),var(--green-dark));color:#fff;display:grid;place-items:center;font-size:23px;font-weight:900}
        .nav-links{display:flex;align-items:center;gap:30px;font-weight:500}
        .nav-links a{color:var(--muted)}
        .nav-links a:hover{color:var(--green)}
        .nav-cta{display:flex;align-items:center;gap:14px}
        .menu-btn{display:none;background:none;border:none;font-size:26px;color:var(--green-dark);cursor:pointer}
        @media(max-width:880px){.nav-links{display:none}.menu-btn{display:block}.nav-cta .btn{display:none}}

        /* Hero */
        .hero{position:relative;padding:74px 0 70px;background:
            radial-gradient(1100px 460px at 88% -8%,var(--green-soft),transparent),
            radial-gradient(700px 360px at 5% 110%,#fff7e6,transparent)}
        .hero-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:48px;align-items:center}
        .pill{display:inline-flex;align-items:center;gap:8px;background:#fff;border:1px solid #d9ece1;color:var(--green-dark);font-weight:700;padding:8px 16px;border-radius:999px;font-size:14px;margin-bottom:20px;box-shadow:0 6px 18px rgba(17,96,59,.06)}
        .pill .dot{width:9px;height:9px;border-radius:50%;background:var(--green);box-shadow:0 0 0 4px rgba(31,157,99,.18)}
        .hero h1{font-size:54px;font-weight:900;line-height:1.18;letter-spacing:-.5px;margin-bottom:20px}
        .hero h1 span{color:var(--green);position:relative}
        .hero p.lead{font-size:20px;color:var(--muted);margin-bottom:24px;max-width:560px}
        .addr{display:inline-flex;align-items:center;gap:8px;color:var(--green-dark);font-weight:700;margin-bottom:26px}
        .store-badges{display:flex;gap:14px;flex-wrap:wrap}
        .store{display:flex;align-items:center;gap:11px;background:var(--ink);color:#fff;padding:12px 22px;border-radius:15px;transition:.2s}
        .store:hover{transform:translateY(-2px);background:#000}
        .store svg{width:27px;height:27px;flex-shrink:0}
        .store .s-small{font-size:11px;opacity:.82;display:block;line-height:1}
        .store .s-big{font-size:17px;font-weight:700;line-height:1.25}
        @media(max-width:880px){.hero-grid{grid-template-columns:1fr}.hero h1{font-size:38px}}

        /* hero collage */
        .collage{position:relative;height:520px}
        .collage img{position:absolute;border-radius:20px;box-shadow:var(--shadow);object-fit:cover}
        .c1{width:62%;height:300px;top:0;left:0;z-index:2}
        .c2{width:46%;height:240px;bottom:24px;right:0;z-index:3;border:6px solid #fff}
        .c3{width:40%;height:180px;bottom:0;left:8%;z-index:4;border:6px solid #fff}
        .float-card{position:absolute;background:#fff;border-radius:16px;box-shadow:var(--shadow);padding:12px 16px;display:flex;align-items:center;gap:10px;font-weight:700;z-index:6}
        .fc-1{top:18px;right:-6px;font-size:14px}
        .fc-1 .ic{width:34px;height:34px;border-radius:9px;background:var(--green-soft);display:grid;place-items:center}
        .fc-2{bottom:-10px;left:-8px;font-size:13px}
        .fc-2 .ic{width:34px;height:34px;border-radius:9px;background:#fff3d6;display:grid;place-items:center}
        @media(max-width:880px){.collage{height:380px;margin-top:14px}}

        /* partners strip */
        .strip{background:var(--ink);color:#bcd0c4;padding:18px 0;overflow:hidden}
        .marquee{display:flex;gap:50px;white-space:nowrap;animation:slide 22s linear infinite;font-weight:700;font-size:18px;opacity:.85}
        @keyframes slide{from{transform:translateX(0)}to{transform:translateX(-50%)}}
        .marquee span{display:inline-flex;align-items:center;gap:10px}

        /* sec head */
        .sec-head{text-align:center;max-width:660px;margin:0 auto 50px}
        .sec-head .tag{color:var(--green);font-weight:800;letter-spacing:1px;font-size:14px}
        .sec-head h2{font-size:38px;font-weight:900;margin:8px 0 12px;letter-spacing:-.5px}
        .sec-head p{color:var(--muted);font-size:18px}

        /* features */
        .features{display:grid;grid-template-columns:repeat(4,1fr);gap:22px}
        @media(max-width:980px){.features{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:540px){.features{grid-template-columns:1fr}}
        .feature{background:var(--white);border:1px solid #e9f2ec;border-radius:var(--radius);padding:30px 24px;transition:.25s}
        .feature:hover{transform:translateY(-6px);box-shadow:var(--shadow);border-color:transparent}
        .feature .ic{width:58px;height:58px;border-radius:16px;background:var(--green-soft);color:var(--green-dark);display:grid;place-items:center;font-size:28px;margin-bottom:18px}
        .feature h3{font-size:20px;font-weight:800;margin-bottom:8px}
        .feature p{color:var(--muted);font-size:15px}

        /* spaces gallery */
        .spaces{background:var(--cream)}
        .gallery{display:grid;grid-template-columns:repeat(3,1fr);grid-auto-rows:200px;gap:18px}
        .gallery a{position:relative;border-radius:18px;overflow:hidden;display:block}
        .gallery img{width:100%;height:100%;object-fit:cover;transition:.5s}
        .gallery a:hover img{transform:scale(1.07)}
        .gallery a::after{content:attr(data-label);position:absolute;inset:auto 0 0 0;padding:14px 16px;color:#fff;font-weight:800;background:linear-gradient(transparent,rgba(0,0,0,.65))}
        .g-tall{grid-row:span 2}
        @media(max-width:780px){.gallery{grid-template-columns:repeat(2,1fr);grid-auto-rows:150px}.g-tall{grid-row:span 2}}

        /* steps */
        .steps-wrap{background:var(--ink);color:#fff;border-radius:30px;padding:58px 40px}
        .steps-wrap h2{text-align:center;font-size:34px;font-weight:900;margin-bottom:44px}
        .steps{display:grid;grid-template-columns:repeat(5,1fr);gap:18px;text-align:center}
        @media(max-width:900px){.steps{grid-template-columns:repeat(2,1fr);gap:28px}}
        .step .n{width:52px;height:52px;border-radius:50%;background:var(--gold);color:var(--ink);font-weight:900;font-size:21px;display:grid;place-items:center;margin:0 auto 14px}
        .step h4{font-size:17px;font-weight:800;margin-bottom:6px}
        .step p{font-size:13px;color:#b9c7bf}

        /* plans */
        .plans{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;align-items:stretch}
        @media(max-width:820px){.plans{grid-template-columns:1fr;max-width:420px;margin:0 auto}}
        .plan{background:#fff;border:1px solid #e9f2ec;border-radius:var(--radius);padding:32px 26px;display:flex;flex-direction:column}
        .plan.featured{border:2px solid var(--green);box-shadow:var(--shadow);position:relative}
        .plan.featured .ribbon{position:absolute;top:-14px;left:50%;transform:translateX(-50%);background:var(--green);color:#fff;font-weight:800;font-size:13px;padding:6px 18px;border-radius:999px}
        .plan h3{font-size:20px;font-weight:900}
        .plan .price{font-size:42px;font-weight:900;color:var(--green-dark);margin:10px 0 2px}
        .plan .price small{font-size:16px;color:var(--muted);font-weight:600}
        .plan ul{list-style:none;margin:18px 0 24px;display:flex;flex-direction:column;gap:11px}
        .plan li{display:flex;gap:9px;color:var(--muted);font-size:15px}
        .plan li::before{content:"✓";color:var(--green);font-weight:900}
        .plan .btn{width:100%;justify-content:center;margin-top:auto}
        .plan .btn-soft{background:var(--green-soft);color:var(--green-dark)}

        /* testimonials */
        .tests{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
        @media(max-width:880px){.tests{grid-template-columns:1fr}}
        .tcard{background:#fff;border:1px solid #e9f2ec;border-radius:var(--radius);padding:28px}
        .tcard .stars{color:var(--gold);font-size:18px;margin-bottom:12px}
        .tcard p{font-size:16px;margin-bottom:18px}
        .tcard .who{display:flex;align-items:center;gap:12px}
        .tcard .av{width:46px;height:46px;border-radius:50%;display:grid;place-items:center;color:#fff;font-weight:800}
        .tcard .who b{display:block;font-size:15px}
        .tcard .who span{font-size:13px;color:var(--muted)}

        /* faq */
        .faq{max-width:780px;margin:0 auto}
        .qa{background:#fff;border:1px solid #e9f2ec;border-radius:14px;margin-bottom:14px;overflow:hidden}
        .qa summary{cursor:pointer;list-style:none;padding:20px 22px;font-weight:700;font-size:17px;display:flex;justify-content:space-between;align-items:center}
        .qa summary::-webkit-details-marker{display:none}
        .qa summary::after{content:"+";color:var(--green);font-size:24px;font-weight:400;transition:.2s}
        .qa[open] summary::after{transform:rotate(45deg)}
        .qa .body{padding:0 22px 20px;color:var(--muted)}

        /* CTA */
        .cta{background:linear-gradient(135deg,var(--green),var(--green-dark));border-radius:30px;color:#fff;text-align:center;padding:60px 30px;position:relative;overflow:hidden}
        .cta::before{content:"";position:absolute;width:300px;height:300px;background:rgba(255,255,255,.08);border-radius:50%;top:-120px;right:-80px}
        .cta h2{font-size:36px;font-weight:900;margin-bottom:12px;position:relative}
        .cta p{opacity:.92;font-size:18px;margin-bottom:28px;position:relative}
        .cta .store{background:#fff;color:var(--ink)}
        .cta .store:hover{background:#f1f1f1}
        .cta .store-badges{justify-content:center;position:relative}

        /* footer */
        footer{background:var(--ink);color:#cdd9d2;padding:50px 0 28px;margin-top:80px}
        .foot-grid{display:flex;justify-content:space-between;gap:30px;flex-wrap:wrap;align-items:center}
        .foot-grid .brand{color:#fff}
        .foot-links{display:flex;gap:24px;flex-wrap:wrap}
        .foot-links a:hover{color:var(--gold)}
        .copy{border-top:1px solid #25382d;margin-top:28px;padding-top:20px;text-align:center;font-size:13px;color:#8ea197}

        /* sticky mobile download */
        .sticky-dl{position:fixed;bottom:0;left:0;right:0;z-index:70;background:#fff;border-top:1px solid #e4efe8;padding:10px 16px;display:none;justify-content:space-between;align-items:center;gap:12px;box-shadow:0 -8px 24px rgba(0,0,0,.08)}
        .sticky-dl b{font-size:15px}
        .sticky-dl small{display:block;color:var(--muted);font-size:12px}
        @media(max-width:880px){.sticky-dl{display:flex}footer{margin-bottom:64px}}
    </style>
</head>
<body>

<div class="topbar">🎓 جديد: <b>اشتراك واحد</b> يفتح لك كل مساحات المذاكرة الشريكة في مصر — حمّل أنيس الآن.</div>

<header class="nav">
    <div class="container nav-inner">
        <a href="#" class="brand"><span class="mark">أ</span> أنيس</a>
        <nav class="nav-links">
            <a href="#features">المميزات</a>
            <a href="#spaces">المساحات</a>
            <a href="#how">كيف يعمل</a>
            <a href="#plans">الأسعار</a>
            <a href="#contact">تواصل</a>
        </nav>
        <div class="nav-cta">
            <a href="#download" class="btn btn-primary">حمّل التطبيق</a>
            <button class="menu-btn" onclick="document.getElementById('m').scrollIntoView()">☰</button>
        </div>
    </div>
</header>

<!-- HERO -->
<section class="hero">
    <div class="container hero-grid">
        <div class="reveal in">
            <span class="pill"><span class="dot"></span> شبكة مساحات المذاكرة للطلاب في مصر</span>
            <h1>اشتراك واحد… <span>كل مساحات المذاكرة</span> قريبة منك</h1>
            <p class="lead">
                أنيس منصة مجتمع طلابي وشبكة مساحات عمل. اشترك مرة واحدة وادخل أي مساحة شريكة بالقرب منك،
                انضم إلى جلسات مذاكرة حية، واعثر على «أنيس» يذاكر معك حسب جامعتك وتخصصك.
            </p>
            <div class="addr">📍 من قلب مصر — انطلاقًا من الجيزة إلى شبكة تتوسّع في كل المدن</div>
            <div class="store-badges" id="download">
                <a href="#" class="store" aria-label="App Store">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16.4 1.6c.1 1-.3 2-1 2.8-.7.8-1.8 1.4-2.8 1.3-.1-1 .4-2 1-2.7.7-.8 1.9-1.4 2.8-1.4zM19 17.2c-.5 1.1-.7 1.6-1.3 2.6-.9 1.4-2.1 3.1-3.7 3.1-1.4 0-1.7-.9-3.6-.9-1.9 0-2.3.9-3.6.9-1.6 0-2.8-1.6-3.6-2.9C.9 16.7.7 12.4 2.3 10c.9-1.3 2.3-2.1 3.7-2.1 1.4 0 2.3.9 3.5.9 1.1 0 1.8-.9 3.5-.9 1.2 0 2.5.7 3.4 1.8-3 1.6-2.5 5.9.1 7.5z"/></svg>
                    <span><span class="s-small">حمّله من</span><span class="s-big">App Store</span></span>
                </a>
                <a href="#" class="store" aria-label="Google Play">
                    <svg viewBox="0 0 24 24"><path fill="#34d399" d="M3.6 2.3 13 11.7l-2.6 2.6L3.6 2.3z"/><path fill="#60a5fa" d="M3.6 21.7 10.4 9.7 13 12.3 3.6 21.7z"/><path fill="#fbbf24" d="m16.2 8.9 3.1 1.8c1 .6 1 1.9 0 2.5l-3.1 1.8-2.8-2.8 2.8-3.3z"/></svg>
                    <span><span class="s-small">حمّله من</span><span class="s-big">Google Play</span></span>
                </a>
            </div>
        </div>

        <div class="reveal in">
            <div class="collage">
                <img class="c1" src="https://picsum.photos/seed/anis-study1/700/400" alt="طلاب يذاكرون معًا" loading="lazy">
                <img class="c2" src="https://picsum.photos/seed/anis-space2/600/400" alt="مساحة عمل" loading="lazy">
                <img class="c3" src="https://picsum.photos/seed/anis-cafe3/500/350" alt="مساحة مذاكرة" loading="lazy">
                <div class="float-card fc-1"><span class="ic">📲</span> تم تسجيل دخولك — جارٍ احتساب وقتك</div>
                <div class="float-card fc-2"><span class="ic">👥</span> 12 طالبًا انضموا لجلسة الفيزياء</div>
            </div>
        </div>
    </div>
</section>

<!-- PARTNER STRIP -->
<div class="strip">
    <div class="marquee">
        <span>🏢 StudyHub المعادي</span><span>📚 Focus Space مدينة نصر</span><span>☕ BrainPark الزمالك</span>
        <span>🤫 Quiet Corner الجيزة</span><span>🏛️ The Library مصر الجديدة</span><span>🏢 Anis Central وسط البلد</span>
        <span>🏢 StudyHub المعادي</span><span>📚 Focus Space مدينة نصر</span><span>☕ BrainPark الزمالك</span>
        <span>🤫 Quiet Corner الجيزة</span><span>🏛️ The Library مصر الجديدة</span><span>🏢 Anis Central وسط البلد</span>
    </div>
</div>

<!-- FEATURES -->
<section id="features">
    <div class="container">
        <div class="sec-head reveal">
            <div class="tag">لماذا أنيس؟</div>
            <h2>كل ما تحتاجه للمذاكرة في مكان واحد</h2>
            <p>اشتراك موحّد، جلسات حية، شركاء مذاكرة، وتسجيل دخول ذكي — كل ذلك من تطبيق واحد.</p>
        </div>
        <div class="features">
            <div class="feature reveal"><div class="ic">🏢</div><h3>اشتراك موحّد</h3><p>اشترك مرة واحدة وادخل أي مساحة عمل شريكة في الشبكة برصيد ساعاتك — بدون اشتراكات متعددة.</p></div>
            <div class="feature reveal"><div class="ic">📅</div><h3>جلسات مذاكرة حية</h3><p>أنشئ أو انضم لجلسات مذاكرة جماعية حسب المادة والجامعة والوقت، وشاهد من سينضم معك.</p></div>
            <div class="feature reveal"><div class="ic">👥</div><h3>ابحث عن أنيسك</h3><p>مطابقة ذكية لشركاء المذاكرة من جامعتك ونفس تخصصك وبالقرب منك — لتتعلّموا معًا.</p></div>
            <div class="feature reveal"><div class="ic">📲</div><h3>دخول بالـ QR</h3><p>امسح كود المساحة لتسجيل حضورك، ويحتسب التطبيق وقت مذاكرتك تلقائيًا من رصيدك عند خروجك.</p></div>
        </div>
    </div>
</section>

<!-- SPACES GALLERY -->
<section class="spaces" id="spaces">
    <div class="container">
        <div class="sec-head reveal">
            <div class="tag">مساحاتنا الشريكة</div>
            <h2>أماكن مصمّمة للتركيز والإنجاز</h2>
            <p>واي‑فاي سريع، تكييف، مشروبات، وأركان هادئة — اختر الأقرب إليك وادخل بمسح الـ QR.</p>
        </div>
        <div class="gallery reveal">
            <a class="g-tall" data-label="Anis Central · وسط البلد"><img src="https://picsum.photos/seed/anis-g1/600/800" alt="مساحة" loading="lazy"></a>
            <a data-label="StudyHub · المعادي"><img src="https://picsum.photos/seed/anis-g2/600/400" alt="مساحة" loading="lazy"></a>
            <a data-label="BrainPark · الزمالك"><img src="https://picsum.photos/seed/anis-g3/600/400" alt="مساحة" loading="lazy"></a>
            <a data-label="Focus Space · مدينة نصر"><img src="https://picsum.photos/seed/anis-g4/600/400" alt="مساحة" loading="lazy"></a>
            <a class="g-tall" data-label="The Library · مصر الجديدة"><img src="https://picsum.photos/seed/anis-g5/600/800" alt="مساحة" loading="lazy"></a>
            <a data-label="Quiet Corner · الجيزة"><img src="https://picsum.photos/seed/anis-g6/600/400" alt="مساحة" loading="lazy"></a>
        </div>
    </div>
</section>

<!-- HOW -->
<section id="how">
    <div class="container">
        <div class="steps-wrap reveal">
            <h2>كيف يعمل أنيس؟</h2>
            <div class="steps">
                <div class="step"><div class="n">1</div><h4>اشترك</h4><p>اختر باقتك وفعّل رصيد ساعاتك.</p></div>
                <div class="step"><div class="n">2</div><h4>اعثر على مساحة</h4><p>تصفّح المساحات الشريكة القريبة منك.</p></div>
                <div class="step"><div class="n">3</div><h4>امسح الـ QR</h4><p>سجّل دخولك للمساحة بضغطة.</p></div>
                <div class="step"><div class="n">4</div><h4>ذاكر وانضم</h4><p>انضم لجلسة أو اعثر على شريك مذاكرة.</p></div>
                <div class="step"><div class="n">5</div><h4>اخرج</h4><p>امسح للخروج ويُحتسب وقتك تلقائيًا.</p></div>
            </div>
        </div>
    </div>
</section>

<!-- PLANS -->
<section id="plans" class="spaces">
    <div class="container">
        <div class="sec-head reveal">
            <div class="tag">الأسعار</div>
            <h2>باقات بسيطة وواضحة</h2>
            <p>ابدأ مجانًا، ثم اختر الباقة التي تناسب ساعات مذاكرتك. كل الأسعار بالجنيه المصري.</p>
        </div>
        <div class="plans reveal">
            <div class="plan">
                <h3>مجاني</h3>
                <div class="price">0 <small>ج.م</small></div>
                <p style="color:var(--muted);font-size:14px">للتجربة والبداية</p>
                <ul>
                    <li>تصفّح المساحات والجلسات</li>
                    <li>رصيد ساعات تجريبي</li>
                    <li>الانضمام لجلسة مذاكرة</li>
                </ul>
                <a href="#download" class="btn btn-soft">ابدأ مجانًا</a>
            </div>
            <div class="plan featured">
                <span class="ribbon">الأكثر شيوعًا</span>
                <h3>Silver — شهري</h3>
                <div class="price">199 <small>ج.م / شهر</small></div>
                <p style="color:var(--muted);font-size:14px">للمذاكرة المنتظمة</p>
                <ul>
                    <li>رصيد ساعات أكبر شهريًا</li>
                    <li>دخول كل المساحات الشريكة</li>
                    <li>إنشاء جلسات مذاكرة</li>
                    <li>مطابقة شركاء المذاكرة</li>
                </ul>
                <a href="#download" class="btn btn-primary">اشترك الآن</a>
            </div>
            <div class="plan">
                <h3>Gold — شهري</h3>
                <div class="price">299 <small>ج.م / شهر</small></div>
                <p style="color:var(--muted);font-size:14px">للمذاكرة المكثّفة</p>
                <ul>
                    <li>أكبر رصيد ساعات</li>
                    <li>أولوية في الجلسات الممتلئة</li>
                    <li>كل مزايا Silver</li>
                </ul>
                <a href="#download" class="btn btn-soft">اختر Gold</a>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section>
    <div class="container">
        <div class="sec-head reveal">
            <div class="tag">آراء الطلاب</div>
            <h2>مذاكرة أفضل مع رفقة أفضل</h2>
        </div>
        <div class="tests">
            <div class="tcard reveal">
                <div class="stars">★★★★★</div>
                <p>«لقيت مكان أذاكر فيه قريب من بيتي بنفس الاشتراك، والجلسات ساعدتني أراجع مع زمايلي قبل الامتحانات.»</p>
                <div class="who"><span class="av" style="background:#3b82f6">س.م</span><div><b>سارة محمد</b><span>هندسة — جامعة القاهرة</span></div></div>
            </div>
            <div class="tcard reveal">
                <div class="stars">★★★★★</div>
                <p>«فكرة الـ QR عبقرية، بمسح وأدخل والوقت بيتحسب لوحده. وفّرت عليّ وقت ومجهود كبير.»</p>
                <div class="who"><span class="av" style="background:#22c55e">أ.ع</span><div><b>أحمد علي</b><span>حاسبات — جامعة عين شمس</span></div></div>
            </div>
            <div class="tcard reveal">
                <div class="stars">★★★★★</div>
                <p>«ابحث عن أنيسك خلّتني ألاقي شريك مذاكرة بنفس تخصصي، بقينا نذاكر مع بعض كل أسبوع.»</p>
                <div class="who"><span class="av" style="background:#ec4899">م.ح</span><div><b>مريم حسن</b><span>طب — جامعة حلوان</span></div></div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="spaces">
    <div class="container">
        <div class="sec-head reveal"><div class="tag">أسئلة شائعة</div><h2>كل ما تريد معرفته</h2></div>
        <div class="faq reveal">
            <details class="qa"><summary>هل الاشتراك يعمل في كل المساحات؟</summary><div class="body">نعم، باشتراك واحد تدخل أي مساحة شريكة في شبكة أنيس برصيد ساعاتك، دون الحاجة لاشتراك منفصل لكل مساحة.</div></details>
            <details class="qa"><summary>كيف يتم احتساب وقت المذاكرة؟</summary><div class="body">تمسح كود الـ QR عند الدخول، وعند الخروج تمسح مرة أخرى، فيحتسب التطبيق المدة تلقائيًا ويخصمها من رصيد ساعاتك.</div></details>
            <details class="qa"><summary>هل يمكنني إنشاء جلسة مذاكرة خاصة بي؟</summary><div class="body">بالتأكيد، يمكنك إنشاء جلسة وتحديد المادة والمكان والوقت وعدد المقاعد، ثم ينضم إليك زملاؤك.</div></details>
            <details class="qa"><summary>هل التطبيق يدعم العربية؟</summary><div class="body">نعم، أنيس يدعم العربية (RTL) والإنجليزية بالكامل مع تواريخ مناسبة للتقويم الدراسي المصري.</div></details>
            <details class="qa"><summary>كيف أتواصل مع الدعم؟</summary><div class="body">عبر قسم الدعم داخل التطبيق أو على البريد amralaa70009@gmail.com — يسعدنا مساعدتك.</div></details>
        </div>
    </div>
</section>

<!-- CTA -->
<section>
    <div class="container">
        <div class="cta reveal">
            <h2>جاهز تبدأ مذاكرتك مع أنيس؟</h2>
            <p>حمّل التطبيق الآن، واشترك مرة واحدة، وادخل أقرب مساحة إليك.</p>
            <div class="store-badges">
                <a href="#" class="store"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M16.4 1.6c.1 1-.3 2-1 2.8-.7.8-1.8 1.4-2.8 1.3-.1-1 .4-2 1-2.7.7-.8 1.9-1.4 2.8-1.4zM19 17.2c-.5 1.1-.7 1.6-1.3 2.6-.9 1.4-2.1 3.1-3.7 3.1-1.4 0-1.7-.9-3.6-.9-1.9 0-2.3.9-3.6.9-1.6 0-2.8-1.6-3.6-2.9C.9 16.7.7 12.4 2.3 10c.9-1.3 2.3-2.1 3.7-2.1 1.4 0 2.3.9 3.5.9 1.1 0 1.8-.9 3.5-.9 1.2 0 2.5.7 3.4 1.8-3 1.6-2.5 5.9.1 7.5z"/></svg><span><span class="s-small">حمّله من</span><span class="s-big">App Store</span></span></a>
                <a href="#" class="store"><svg viewBox="0 0 24 24"><path fill="#34d399" d="M3.6 2.3 13 11.7l-2.6 2.6L3.6 2.3z"/><path fill="#60a5fa" d="M3.6 21.7 10.4 9.7 13 12.3 3.6 21.7z"/><path fill="#fbbf24" d="m16.2 8.9 3.1 1.8c1 .6 1 1.9 0 2.5l-3.1 1.8-2.8-2.8 2.8-3.3z"/></svg><span><span class="s-small">حمّله من</span><span class="s-big">Google Play</span></span></a>
            </div>
        </div>
    </div>
</section>

<!-- CONTACT / FOOTER -->
<footer id="contact">
    <div class="container">
        <div class="foot-grid">
            <div>
                <a href="#" class="brand"><span class="mark">أ</span> أنيس</a>
                <p style="margin-top:12px;max-width:340px;color:#9fb3a8;font-size:14px">منصة المجتمع الطلابي وشبكة مساحات المذاكرة في مصر. المساحة، والجلسة، والرفيق — في تطبيق واحد.</p>
            </div>
            <div>
                <div style="display:flex;flex-direction:column;gap:8px;font-size:14px">
                    <span>✉️ <a href="mailto:amralaa70009@gmail.com">amralaa70009@gmail.com</a></span>
                    <span dir="ltr" style="text-align:right">📞 <a href="tel:+201011577033">+20 101 157 7033</a></span>
                    <span>📍 الجيزة، جمهورية مصر العربية</span>
                    <span>🌐 <a href="http://www.anis-app.com" target="_blank" rel="noopener">www.anis-app.com</a></span>
                </div>
            </div>
            <nav class="foot-links">
                <a href="#features">المميزات</a>
                <a href="#plans">الأسعار</a>
                <a href="https://sites.google.com/view/anis-privacy-policy/home" target="_blank" rel="noopener">سياسة الخصوصية</a>
            </nav>
        </div>
        <div class="copy">© {{ date('Y') }} أنيس — جميع الحقوق محفوظة.</div>
    </div>
</footer>

<!-- sticky mobile download -->
<div class="sticky-dl">
    <div><b>حمّل تطبيق أنيس</b><small>اشتراك واحد لكل المساحات</small></div>
    <a href="#download" class="btn btn-primary" style="padding:10px 20px">تحميل</a>
</div>

<span id="m"></span>
<script>
    // reveal on scroll
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(el => io.observe(el));
</script>
</body>
</html>
