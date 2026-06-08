<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أنيس — ذاكر مع ناس، مش لوحدك</title>
    <meta name="description" content="أنيس: اشتراك واحد يفتح لك كل مساحات المذاكرة الشريكة في مصر، جلسات مذاكرة حية، ورفيق مذاكرة يشرح لك ويحمّسك.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root{
            --green:#14a800; --green-dark:#108a00; --green-ink:#0c5c00;
            --green-soft:#eaf8e6; --ink:#0a1f12; --muted:#5d6b61;
            --cream:#f6f9f4; --bg:#ffffff; --line:#e6ece7; --gold:#f4b740;
            --radius:22px; --shadow:0 20px 50px rgba(16,138,0,.10); --shadow-lg:0 30px 70px rgba(16,138,0,.16);
        }
        *{margin:0;padding:0;box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{font-family:'Tajawal',sans-serif;color:var(--ink);background:var(--bg);line-height:1.7;overflow-x:hidden}
        a{text-decoration:none;color:inherit}
        img{max-width:100%;display:block}
        .container{max-width:1200px;margin:0 auto;padding:0 24px}
        section{padding:96px 0}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:15px 30px;border-radius:999px;font-weight:800;font-size:16px;transition:.2s;border:2px solid transparent;cursor:pointer;font-family:inherit}
        .btn-primary{background:var(--green);color:#fff}
        .btn-primary:hover{background:var(--green-dark);transform:translateY(-2px);box-shadow:0 12px 26px rgba(20,168,0,.3)}
        .btn-outline{background:#fff;color:var(--green-ink);border-color:var(--green)}
        .btn-outline:hover{background:var(--green-soft)}
        .reveal{opacity:0;transform:translateY(28px);transition:.7s cubic-bezier(.2,.7,.2,1)}
        .reveal.in{opacity:1;transform:none}
        .tag{display:inline-block;color:var(--green-dark);font-weight:800;letter-spacing:1.5px;font-size:13px;text-transform:uppercase}

        .topbar{background:var(--ink);color:#dff3e8;text-align:center;font-size:14px;padding:10px 16px;font-weight:500}
        .topbar b{color:var(--gold)}

        header.nav{position:sticky;top:0;z-index:60;background:rgba(255,255,255,.9);backdrop-filter:blur(14px);border-bottom:1px solid var(--line)}
        .nav-inner{display:flex;align-items:center;justify-content:space-between;height:78px}
        .brand{display:flex;align-items:center;gap:11px;font-weight:900;font-size:26px;color:var(--ink)}
        .brand .mark{width:46px;height:46px;border-radius:14px;background:linear-gradient(135deg,var(--green),var(--green-dark));color:#fff;display:grid;place-items:center;font-size:24px;font-weight:900}
        .nav-links{display:flex;align-items:center;gap:32px;font-weight:600}
        .nav-links a{color:var(--muted)}
        .nav-links a:hover{color:var(--green-dark)}
        .menu-btn{display:none;background:none;border:none;font-size:26px;cursor:pointer}
        @media(max-width:900px){.nav-links{display:none}.menu-btn{display:block}}

        /* HERO */
        .hero{padding:90px 0 80px;background:radial-gradient(900px 420px at 90% 0%,var(--green-soft),transparent)}
        .hero-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:54px;align-items:center}
        .pill{display:inline-flex;align-items:center;gap:8px;background:#fff;border:1px solid var(--line);color:var(--green-dark);font-weight:700;padding:9px 18px;border-radius:999px;font-size:14px;margin-bottom:22px;box-shadow:0 6px 18px rgba(16,138,0,.06)}
        .pill .dot{width:9px;height:9px;border-radius:50%;background:var(--green);box-shadow:0 0 0 4px rgba(20,168,0,.18)}
        .hero h1{font-size:60px;font-weight:900;line-height:1.14;letter-spacing:-1px;margin-bottom:22px}
        .hero h1 .hl{color:var(--green);position:relative;white-space:nowrap}
        .hero h1 .hl::after{content:"";position:absolute;left:0;right:0;bottom:6px;height:14px;background:rgba(20,168,0,.16);z-index:-1;border-radius:6px}
        .hero p.lead{font-size:21px;color:var(--muted);margin-bottom:26px;max-width:560px}
        .searchbar{display:flex;align-items:center;background:#fff;border:1px solid var(--line);border-radius:999px;padding:7px 7px 7px 22px;max-width:480px;box-shadow:var(--shadow);margin-bottom:22px}
        .searchbar span{color:var(--muted);flex:1;font-weight:600}
        .searchbar .btn{padding:13px 26px}
        .trust{display:flex;align-items:center;gap:18px;color:var(--muted);font-weight:600;font-size:14px;flex-wrap:wrap}
        .trust .stars{color:var(--gold)}
        @media(max-width:900px){.hero-grid{grid-template-columns:1fr}.hero h1{font-size:40px}}

        .hero-visual{position:relative}
        .hero-img{border-radius:28px;box-shadow:var(--shadow-lg);width:100%;height:480px;object-fit:cover}
        .float-card{position:absolute;background:#fff;border-radius:16px;box-shadow:var(--shadow);padding:14px 18px;display:flex;align-items:center;gap:12px;font-weight:800;font-size:14px}
        .float-card .ic{width:40px;height:40px;border-radius:11px;display:grid;place-items:center;font-size:18px}
        .fc-1{top:24px;right:-14px}.fc-1 .ic{background:var(--green-soft)}
        .fc-2{bottom:28px;left:-16px}.fc-2 .ic{background:#fff3d6}
        .fc-2 small{display:block;color:var(--muted);font-weight:600;font-size:12px}
        @media(max-width:900px){.hero-img{height:340px}.float-card{display:none}}

        /* LOGO STRIP */
        .strip{padding:26px 0;border-top:1px solid var(--line);border-bottom:1px solid var(--line);overflow:hidden;background:var(--cream)}
        .strip .lbl{text-align:center;color:var(--muted);font-weight:700;font-size:13px;margin-bottom:14px}
        .marquee{display:flex;gap:46px;white-space:nowrap;animation:slide 24s linear infinite;font-weight:800;font-size:18px;color:#9aa8a0}
        @keyframes slide{from{transform:translateX(0)}to{transform:translateX(-50%)}}
        .marquee span{display:inline-flex;align-items:center;gap:8px}

        /* MOTIVATION BAND */
        .motiv{background:linear-gradient(135deg,#0c5c00,#108a00);color:#fff;position:relative;overflow:hidden}
        .motiv::before,.motiv::after{content:"";position:absolute;border-radius:50%;background:rgba(255,255,255,.06)}
        .motiv::before{width:360px;height:360px;top:-140px;right:-120px}
        .motiv::after{width:260px;height:260px;bottom:-120px;left:-80px}
        .motiv .inner{position:relative;text-align:center;max-width:900px;margin:0 auto}
        .motiv .tag{color:var(--gold)}
        .motiv h2{font-size:46px;font-weight:900;line-height:1.25;margin:12px 0 16px;letter-spacing:-.5px}
        .motiv h2 .u{color:var(--gold)}
        .motiv .sub{font-size:20px;opacity:.92;max-width:680px;margin:0 auto 44px}
        .punch{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;text-align:right}
        @media(max-width:840px){.punch{grid-template-columns:1fr}.motiv h2{font-size:32px}}
        .punch .p{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);border-radius:20px;padding:26px}
        .punch .p .emo{font-size:30px;margin-bottom:10px}
        .punch .p h4{font-size:20px;font-weight:900;margin-bottom:8px}
        .punch .p p{opacity:.9;font-size:15px}

        /* SECTION HEAD */
        .sec-head{text-align:center;max-width:680px;margin:0 auto 56px}
        .sec-head h2{font-size:42px;font-weight:900;margin:10px 0 14px;letter-spacing:-.6px}
        .sec-head p{color:var(--muted);font-size:18px}

        /* FEATURES */
        .features{display:grid;grid-template-columns:repeat(4,1fr);gap:24px}
        @media(max-width:980px){.features{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:540px){.features{grid-template-columns:1fr}}
        .feature{background:#fff;border:1px solid var(--line);border-radius:var(--radius);padding:32px 26px;transition:.25s}
        .feature:hover{transform:translateY(-6px);box-shadow:var(--shadow-lg);border-color:transparent}
        .feature .ic{width:60px;height:60px;border-radius:17px;background:var(--green-soft);color:var(--green-dark);display:grid;place-items:center;font-size:29px;margin-bottom:20px}
        .feature h3{font-size:21px;font-weight:900;margin-bottom:8px}
        .feature p{color:var(--muted);font-size:15px}

        /* SPACES */
        .spaces{background:var(--cream)}
        .gallery{display:grid;grid-template-columns:repeat(3,1fr);grid-auto-rows:210px;gap:18px}
        .gallery a{position:relative;border-radius:20px;overflow:hidden;display:block}
        .gallery img{width:100%;height:100%;object-fit:cover;transition:.5s}
        .gallery a:hover img{transform:scale(1.07)}
        .gallery a::after{content:attr(data-label);position:absolute;inset:auto 0 0 0;padding:16px;color:#fff;font-weight:800;background:linear-gradient(transparent,rgba(0,0,0,.7))}
        .g-tall{grid-row:span 2}
        @media(max-width:780px){.gallery{grid-template-columns:repeat(2,1fr);grid-auto-rows:150px}}

        /* STEPS */
        .steps-wrap{background:var(--ink);color:#fff;border-radius:32px;padding:62px 44px}
        .steps-wrap h2{text-align:center;font-size:36px;font-weight:900;margin-bottom:48px}
        .steps{display:grid;grid-template-columns:repeat(5,1fr);gap:18px;text-align:center}
        @media(max-width:900px){.steps{grid-template-columns:repeat(2,1fr);gap:30px}}
        .step .n{width:54px;height:54px;border-radius:50%;background:var(--gold);color:var(--ink);font-weight:900;font-size:22px;display:grid;place-items:center;margin:0 auto 14px}
        .step h4{font-size:17px;font-weight:900;margin-bottom:6px}
        .step p{font-size:13px;color:#b9c7bf}

        /* PLANS */
        .plans{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;align-items:stretch}
        @media(max-width:820px){.plans{grid-template-columns:1fr;max-width:430px;margin:0 auto}}
        .plan{background:#fff;border:1px solid var(--line);border-radius:var(--radius);padding:34px 28px;display:flex;flex-direction:column}
        .plan.featured{border:2px solid var(--green);box-shadow:var(--shadow-lg);position:relative}
        .plan.featured .ribbon{position:absolute;top:-14px;left:50%;transform:translateX(-50%);background:var(--green);color:#fff;font-weight:800;font-size:13px;padding:6px 18px;border-radius:999px}
        .plan h3{font-size:21px;font-weight:900}
        .plan .price{font-size:44px;font-weight:900;color:var(--green-dark);margin:10px 0 2px}
        .plan .price small{font-size:16px;color:var(--muted);font-weight:600}
        .plan ul{list-style:none;margin:18px 0 26px;display:flex;flex-direction:column;gap:12px}
        .plan li{display:flex;gap:9px;color:var(--muted);font-size:15px}
        .plan li::before{content:"✓";color:var(--green);font-weight:900}
        .plan .btn{width:100%;margin-top:auto}

        /* TESTIMONIALS */
        .tests{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
        @media(max-width:880px){.tests{grid-template-columns:1fr}}
        .tcard{background:#fff;border:1px solid var(--line);border-radius:var(--radius);padding:30px}
        .tcard .stars{color:var(--gold);font-size:18px;margin-bottom:12px}
        .tcard p{font-size:16px;margin-bottom:18px}
        .tcard .who{display:flex;align-items:center;gap:12px}
        .tcard .av{width:48px;height:48px;border-radius:50%;display:grid;place-items:center;color:#fff;font-weight:800}
        .tcard .who b{display:block;font-size:15px}
        .tcard .who span{font-size:13px;color:var(--muted)}

        /* FAQ */
        .faq{max-width:800px;margin:0 auto}
        .qa{background:#fff;border:1px solid var(--line);border-radius:16px;margin-bottom:14px;overflow:hidden}
        .qa summary{cursor:pointer;list-style:none;padding:22px 24px;font-weight:800;font-size:17px;display:flex;justify-content:space-between;align-items:center}
        .qa summary::-webkit-details-marker{display:none}
        .qa summary::after{content:"+";color:var(--green);font-size:26px;font-weight:400;transition:.2s}
        .qa[open] summary::after{transform:rotate(45deg)}
        .qa .body{padding:0 24px 22px;color:var(--muted)}

        /* CTA */
        .cta{background:linear-gradient(135deg,var(--green),var(--green-dark));border-radius:34px;color:#fff;text-align:center;padding:66px 30px;position:relative;overflow:hidden}
        .cta::before{content:"";position:absolute;width:340px;height:340px;background:rgba(255,255,255,.08);border-radius:50%;top:-140px;right:-90px}
        .cta h2{font-size:40px;font-weight:900;margin-bottom:14px;position:relative}
        .cta p{opacity:.93;font-size:19px;margin-bottom:30px;position:relative}
        .store-badges{display:flex;gap:14px;flex-wrap:wrap;justify-content:center;position:relative}
        .store{display:flex;align-items:center;gap:11px;background:#fff;color:var(--ink);padding:13px 24px;border-radius:15px;transition:.2s}
        .store:hover{transform:translateY(-2px)}
        .store svg{width:27px;height:27px;flex-shrink:0}
        .store .s-small{font-size:11px;opacity:.75;display:block;line-height:1}
        .store .s-big{font-size:17px;font-weight:800;line-height:1.25}

        footer{background:var(--ink);color:#cdd9d2;padding:56px 0 28px;margin-top:90px}
        .foot-grid{display:flex;justify-content:space-between;gap:32px;flex-wrap:wrap;align-items:flex-start}
        .foot-grid .brand{color:#fff}
        .foot-links{display:flex;flex-direction:column;gap:10px}
        .foot-links a:hover{color:var(--gold)}
        .copy{border-top:1px solid #25382d;margin-top:30px;padding-top:20px;text-align:center;font-size:13px;color:#8ea197}

        .sticky-dl{position:fixed;bottom:0;left:0;right:0;z-index:70;background:#fff;border-top:1px solid var(--line);padding:11px 16px;display:none;justify-content:space-between;align-items:center;gap:12px;box-shadow:0 -8px 24px rgba(0,0,0,.08)}
        .sticky-dl b{font-size:15px}.sticky-dl small{display:block;color:var(--muted);font-size:12px}
        @media(max-width:900px){.sticky-dl{display:flex}footer{margin-bottom:66px}}
    </style>
</head>
<body>
<div class="topbar">🎓 <b>اشتراك واحد</b> يفتح لك كل مساحات المذاكرة الشريكة في مصر — حمّل أنيس الآن.</div>

<header class="nav"><div class="container nav-inner">
    <a href="#" class="brand"><span class="mark">أ</span> أنيس</a>
    <nav class="nav-links">
        <a href="#why">ليه أنيس</a><a href="#features">المميزات</a><a href="#spaces">المساحات</a><a href="#plans">الأسعار</a><a href="{{ route('workspace.register') }}" style="color: var(--green); font-weight: 700;">بوابة الشركاء</a><a href="#contact">تواصل</a>
    </nav>
    <a href="#download" class="btn btn-primary">حمّل التطبيق</a>
    <button class="menu-btn" onclick="location.href='#download'">☰</button>
</div></header>

<!-- HERO -->
<section class="hero"><div class="container hero-grid">
    <div class="reveal in">
        <span class="pill"><span class="dot"></span> شبكة مساحات المذاكرة للطلاب في مصر</span>
        <h1>ذاكر مع ناس… <span class="hl">مش لوحدك</span> لحد ما تنام</h1>
        <p class="lead">اشتراك واحد يفتح لك أقرب مساحة، جلسات مذاكرة حية، ورفيق يشرح لك اللي صعب ويحمّسك. المذاكرة بقت أمتع وأسهل.</p>
        <div class="searchbar">
            <span>🔍 دوّر على مساحة أو زميل مذاكرة قريب منك…</span>
            <a href="#download" class="btn btn-primary">ابدأ</a>
        </div>
        <div class="trust"><span class="stars">★★★★★</span><span>يحبّه الطلاب في مختلف الجامعات</span><span>·</span><span>iOS و Android</span></div>
    </div>
    <div class="reveal in hero-visual">
        <img class="hero-img" src="https://picsum.photos/seed/anis-hero-main/900/700" alt="طلاب يذاكرون معًا">
        <div class="float-card fc-1"><span class="ic">📲</span> تم تسجيل دخولك — جارٍ احتساب وقتك</div>
        <div class="float-card fc-2"><span class="ic">👥</span> <div>12 طالبًا انضموا<small>جلسة الفيزياء — الآن</small></div></div>
    </div>
</div></section>

<!-- LOGO STRIP -->
<div class="strip"><div class="container">
    <div class="lbl">مساحاتنا الشريكة في مصر</div>
    <div class="marquee">
        <span>🏢 Anis Central</span><span>📚 StudyHub المعادي</span><span>☕ BrainPark الزمالك</span><span>🤫 Quiet Corner الجيزة</span><span>🏛️ The Library مصر الجديدة</span><span>📚 Focus Space مدينة نصر</span>
        <span>🏢 Anis Central</span><span>📚 StudyHub المعادي</span><span>☕ BrainPark الزمالك</span><span>🤫 Quiet Corner الجيزة</span><span>🏛️ The Library مصر الجديدة</span><span>📚 Focus Space مدينة نصر</span>
    </div>
</div></div>

<!-- MOTIVATION BAND -->
<section class="motiv" id="why"><div class="container inner reveal">
    <span class="tag">المذاكرة مع أنيس</span>
    <h2>بطّل تذاكر لوحدك وتنام… <span class="u">ذاكر مع ناس وانجز</span></h2>
    <p class="sub">المذاكرة مش مملة ولا صعبة بعد كده. أي حاجة تصعب عليك، هتلاقي زميل يبسّطها لك ويشرحها، وتشجّعوا بعض لحد ما تخلّصوا — مش لوحدك تزهق وتستسلم.</p>
    <div class="punch">
        <div class="p"><div class="emo">🧠</div><h4>صعبة عليك؟ مش مشكلة.</h4><p>هتلاقي زميل فاهم المادة يبسّطها لك ويشرحلك اللي مش واضح — في دقايق مش أيام.</p></div>
        <div class="p"><div class="emo">🔥</div><h4>بتزهق لوحدك؟ خلاص.</h4><p>ذاكر مع مجموعة من نفس مادتك، حمّسوا بعض، والتحدّي بينكم يخلّيك تنجز أكتر.</p></div>
        <div class="p"><div class="emo">⏰</div><h4>بتنام وانت بتذاكر؟</h4><p>مع رفقة المذاكرة الوقت بيعدي وانت مركّز ومبسوط — مفيش ملل ولا استسلام.</p></div>
    </div>
</div></section>

<!-- FEATURES -->
<section id="features"><div class="container">
    <div class="sec-head reveal"><div class="tag">ليه أنيس؟</div><h2>كل ما تحتاجه للمذاكرة في مكان واحد</h2><p>اشتراك موحّد، جلسات حية، رفيق مذاكرة، وتسجيل دخول ذكي — من تطبيق واحد.</p></div>
    <div class="features">
        <div class="feature reveal"><div class="ic">🏢</div><h3>اشتراك موحّد</h3><p>اشترك مرة واحدة وادخل أي مساحة شريكة في الشبكة برصيد ساعاتك — بدون اشتراكات متعددة.</p></div>
        <div class="feature reveal"><div class="ic">📅</div><h3>جلسات مذاكرة حية</h3><p>أنشئ أو انضم لجلسات جماعية حسب المادة والجامعة والوقت، وشوف مين هينضم معك.</p></div>
        <div class="feature reveal"><div class="ic">👥</div><h3>ابحث عن أنيسك</h3><p>مطابقة ذكية لرفيق مذاكرة من جامعتك ونفس تخصصك وبالقرب منك — تذاكروا وتساعدوا بعض.</p></div>
        <div class="feature reveal"><div class="ic">📲</div><h3>دخول بالـ QR</h3><p>امسح كود المساحة لتسجيل حضورك، ويحتسب التطبيق وقتك تلقائيًا من رصيدك عند خروجك.</p></div>
    </div>
</div></section>

<!-- SPACES -->
<section class="spaces" id="spaces"><div class="container">
    <div class="sec-head reveal"><div class="tag">مساحاتنا الشريكة</div><h2>أماكن مصمّمة للتركيز والإنجاز</h2><p>واي‑فاي سريع، تكييف، مشروبات، وأركان هادئة — اختر الأقرب وادخل بمسح الـ QR.</p></div>
    <div class="gallery reveal">
        <a class="g-tall" data-label="Anis Central · وسط البلد"><img src="https://picsum.photos/seed/anis-g1/600/800" alt="" loading="lazy"></a>
        <a data-label="StudyHub · المعادي"><img src="https://picsum.photos/seed/anis-g2/600/400" alt="" loading="lazy"></a>
        <a data-label="BrainPark · الزمالك"><img src="https://picsum.photos/seed/anis-g3/600/400" alt="" loading="lazy"></a>
        <a data-label="Focus Space · مدينة نصر"><img src="https://picsum.photos/seed/anis-g4/600/400" alt="" loading="lazy"></a>
        <a class="g-tall" data-label="The Library · مصر الجديدة"><img src="https://picsum.photos/seed/anis-g5/600/800" alt="" loading="lazy"></a>
        <a data-label="Quiet Corner · الجيزة"><img src="https://picsum.photos/seed/anis-g6/600/400" alt="" loading="lazy"></a>
    </div>
</div></section>

<!-- STEPS -->
<section><div class="container"><div class="steps-wrap reveal">
    <h2>كيف يعمل أنيس؟</h2>
    <div class="steps">
        <div class="step"><div class="n">1</div><h4>اشترك</h4><p>اختر باقتك وفعّل رصيد ساعاتك.</p></div>
        <div class="step"><div class="n">2</div><h4>اعثر على مساحة</h4><p>تصفّح المساحات القريبة منك.</p></div>
        <div class="step"><div class="n">3</div><h4>امسح الـ QR</h4><p>سجّل دخولك للمساحة بضغطة.</p></div>
        <div class="step"><div class="n">4</div><h4>ذاكر وانضم</h4><p>انضم لجلسة أو لاقِ رفيق مذاكرة.</p></div>
        <div class="step"><div class="n">5</div><h4>اخرج</h4><p>امسح للخروج ويُحتسب وقتك تلقائيًا.</p></div>
    </div>
</div></div></section>

<!-- PLANS -->
<section id="plans" class="spaces"><div class="container">
    <div class="sec-head reveal"><div class="tag">الأسعار</div><h2>باقات بسيطة وواضحة</h2><p>ابدأ مجانًا، ثم اختر الباقة التي تناسب ساعات مذاكرتك. كل الأسعار بالجنيه المصري.</p></div>
    <div class="plans reveal">
        @forelse($plans ?? [] as $plan)
            <div class="plan {{ $plan->tier->value === 'SILVER' ? 'featured' : '' }}">
                @if($plan->tier->value === 'SILVER')
                    <span class="ribbon">الأكثر شيوعًا</span>
                @endif
                <h3>{{ $plan->name }}</h3>
                <div class="price">
                    {{ number_format($plan->price_cents / 100, 0) }}
                    <small>ج.م / @if($plan->duration_days == 30) شهر @else {{ $plan->duration_days }} يوم @endif</small>
                </div>
                <p style="color:var(--muted);font-size:14px">
                    @if($plan->tier->value === 'FREE')
                        للتجربة والبداية
                    @elseif($plan->tier->value === 'SILVER')
                        للمذاكرة المنتظمة
                    @else
                        للمذاكرة المكثّفة
                    @endif
                </p>
                <ul>
                    @if($plan->tier->value === 'FREE')
                        <li>تصفّح المساحات والجلسات</li>
                        <li>رصيد ساعات تجريبي</li>
                        <li>الانضمام لجلسة مذاكرة</li>
                    @elseif($plan->tier->value === 'SILVER')
                        <li>رصيد ساعات: {{ number_format($plan->included_minutes / 60, 0) }} ساعة شهرياً</li>
                        <li>دخول كل المساحات الشريكة</li>
                        <li>إنشاء جلسات مذاكرة</li>
                        <li>مطابقة رفقاء المذاكرة</li>
                    @else
                        <li>رصيد ساعات: {{ number_format($plan->included_minutes / 60, 0) }} ساعة شهرياً</li>
                        <li>أولوية في الجلسات الممتلئة</li>
                        <li>كل مزايا Silver</li>
                    @endif
                </ul>
                <a href="#download" class="btn {{ $plan->tier->value === 'SILVER' ? 'btn-primary' : 'btn-outline' }}">
                    @if($plan->tier->value === 'FREE')
                        ابدأ مجانًا
                    @else
                        اشترك الآن
                    @endif
                </a>
            </div>
        @empty
            <div class="plan"><h3>مجاني</h3><div class="price">0 <small>ج.م</small></div><p style="color:var(--muted);font-size:14px">للتجربة والبداية</p><ul><li>تصفّح المساحات والجلسات</li><li>رصيد ساعات تجريبي</li><li>الانضمام لجلسة مذاكرة</li></ul><a href="#download" class="btn btn-outline">ابدأ مجانًا</a></div>
            <div class="plan featured"><span class="ribbon">الأكثر شيوعًا</span><h3>Silver — شهري</h3><div class="price">199 <small>ج.م / شهر</small></div><p style="color:var(--muted);font-size:14px">للمذاكرة المنتظمة</p><ul><li>رصيد ساعات أكبر شهريًا</li><li>دخول كل المساحات الشريكة</li><li>إنشاء جلسات مذاكرة</li><li>مطابقة رفقاء المذاكرة</li></ul><a href="#download" class="btn btn-primary">اشترك الآن</a></div>
            <div class="plan"><h3>Gold — شهري</h3><div class="price">299 <small>ج.م / شهر</small></div><p style="color:var(--muted);font-size:14px">للمذاكرة المكثّفة</p><ul><li>أكبر رصيد ساعات</li><li>أولوية في الجلسات الممتلئة</li><li>كل مزايا Silver</li></ul><a href="#download" class="btn btn-outline">اختر Gold</a></div>
        @endforelse
    </div>
</div></section>

<!-- PARTNER CTA -->
<section style="background:var(--cream); border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); padding: 80px 0;"><div class="container"><div class="cta reveal" style="background:linear-gradient(135deg,var(--ink),#0c2516); color:#fff; text-align:center; padding:50px 30px; border-radius:24px;">
    <div class="tag" style="color:var(--green); font-weight:800; font-size:14px; margin-bottom:12px;">شركاء أنيس</div>
    <h2 style="font-size:32px; font-weight:900; margin-bottom:14px; color:#fff;">أنت صاحب مساحة عمل أو مذاكرة؟</h2>
    <p style="font-size:18px; opacity:0.9; margin-bottom:28px; max-width:640px; margin-left:auto; margin-right:auto; color:#dff3e8;">
        انضم إلى شبكتنا وسجل مساحتك وتفاصيلها ومعرض صورها لجذب آلاف الطلاب والباحثين والمستقلين يومياً وزيادة عوائدك.
    </p>
    <a href="{{ route('workspace.register') }}" class="btn btn-primary" style="background:var(--green); border-color:var(--green);">سجل مساحتك كشريك معنا</a>
</div></div></section>

<!-- CTA -->
<section id="download"><div class="container"><div class="cta reveal">
    <h2>جاهز تبدأ مذاكرتك مع أنيس؟</h2><p>حمّل التطبيق، اشترك مرة واحدة، ولاقِ مساحتك ورفيقك.</p>
    <div class="store-badges">
        <a href="#" class="store"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M16.4 1.6c.1 1-.3 2-1 2.8-.7.8-1.8 1.4-2.8 1.3-.1-1 .4-2 1-2.7.7-.8 1.9-1.4 2.8-1.4zM19 17.2c-.5 1.1-.7 1.6-1.3 2.6-.9 1.4-2.1 3.1-3.7 3.1-1.4 0-1.7-.9-3.6-.9-1.9 0-2.3.9-3.6.9-1.6 0-2.8-1.6-3.6-2.9C.9 16.7.7 12.4 2.3 10c.9-1.3 2.3-2.1 3.7-2.1 1.4 0 2.3.9 3.5.9 1.1 0 1.8-.9 3.5-.9 1.2 0 2.5.7 3.4 1.8-3 1.6-2.5 5.9.1 7.5z"/></svg><span><span class="s-small">حمّله من</span><span class="s-big">App Store</span></span></a>
        <a href="#" class="store"><svg viewBox="0 0 24 24"><path fill="#14a800" d="M3.6 2.3 13 11.7l-2.6 2.6L3.6 2.3z"/><path fill="#60a5fa" d="M3.6 21.7 10.4 9.7 13 12.3 3.6 21.7z"/><path fill="#fbbf24" d="m16.2 8.9 3.1 1.8c1 .6 1 1.9 0 2.5l-3.1 1.8-2.8-2.8 2.8-3.3z"/></svg><span><span class="s-small">حمّله من</span><span class="s-big">Google Play</span></span></a>
    </div>
</div></div></section>

<!-- TESTIMONIALS -->
<section><div class="container">
    <div class="sec-head reveal"><div class="tag">آراء الطلاب</div><h2>مذاكرة أفضل مع رفقة أفضل</h2></div>
    <div class="tests">
        <div class="tcard reveal"><div class="stars">★★★★★</div><p>«كنت بزهق وأنام وأنا بذاكر لوحدي. دلوقتي بذاكر مع مجموعة وبننجز ونضحك — المذاكرة بقت ممتعة.»</p><div class="who"><span class="av" style="background:#14a800">س.م</span><div><b>سارة محمد</b><span>هندسة — جامعة القاهرة</span></div></div></div>
        <div class="tcard reveal"><div class="stars">★★★★★</div><p>«كان في مواد صعبة عليّ، لقيت زميل شرحها لي في ساعة. فكرة الرفيق دي غيّرت مذاكرتي.»</p><div class="who"><span class="av" style="background:#0c5c00">أ.ع</span><div><b>أحمد علي</b><span>حاسبات — جامعة عين شمس</span></div></div></div>
        <div class="tcard reveal"><div class="stars">★★★★★</div><p>«باشتراك واحد بدخل أقرب مساحة ليّ، وفكرة الـ QR بتحسب وقتي لوحدها. وفّرت عليّ كتير.»</p><div class="who"><span class="av" style="background:#f4b740;color:#10241a">م.ح</span><div><b>مريم حسن</b><span>طب — جامعة حلوان</span></div></div></div>
    </div>
</div></section>

<!-- FAQ -->
<section class="spaces"><div class="container">
    <div class="sec-head reveal"><div class="tag">أسئلة شائعة</div><h2>كل ما تريد معرفته</h2></div>
    <div class="faq reveal">
        <details class="qa"><summary>هل الاشتراك يعمل في كل المساحات؟</summary><div class="body">نعم، باشتراك واحد تدخل أي مساحة شريكة في شبكة أنيس برصيد ساعاتك، دون اشتراك منفصل لكل مساحة.</div></details>
        <details class="qa"><summary>إزاي ألاقي رفيق يشرح لي المادة الصعبة؟</summary><div class="body">من قسم «ابحث عن أنيسك» — التطبيق يطابقك مع طلاب من جامعتك ونفس تخصصك، وتقدر تنضم لجلسة مذاكرة وتسأل وتتساعدوا.</div></details>
        <details class="qa"><summary>كيف يتم احتساب وقت المذاكرة؟</summary><div class="body">تمسح كود الـ QR عند الدخول، وعند الخروج تمسح مرة أخرى، فيحتسب التطبيق المدة تلقائيًا ويخصمها من رصيد ساعاتك.</div></details>
        <details class="qa"><summary>هل التطبيق يدعم العربية؟</summary><div class="body">نعم، أنيس يدعم العربية (RTL) والإنجليزية بالكامل.</div></details>
        <details class="qa"><summary>كيف أتواصل مع الدعم؟</summary><div class="body">عبر قسم الدعم داخل التطبيق أو على البريد amralaa70009@gmail.com.</div></details>
    </div>
</div></section>

<!-- CTA -->
<section id="download"><div class="container"><div class="cta reveal">
    <h2>جاهز تبدأ مذاكرتك مع أنيس؟</h2><p>حمّل التطبيق، اشترك مرة واحدة، ولاقِ مساحتك ورفيقك.</p>
    <div class="store-badges">
        <a href="#" class="store"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M16.4 1.6c.1 1-.3 2-1 2.8-.7.8-1.8 1.4-2.8 1.3-.1-1 .4-2 1-2.7.7-.8 1.9-1.4 2.8-1.4zM19 17.2c-.5 1.1-.7 1.6-1.3 2.6-.9 1.4-2.1 3.1-3.7 3.1-1.4 0-1.7-.9-3.6-.9-1.9 0-2.3.9-3.6.9-1.6 0-2.8-1.6-3.6-2.9C.9 16.7.7 12.4 2.3 10c.9-1.3 2.3-2.1 3.7-2.1 1.4 0 2.3.9 3.5.9 1.1 0 1.8-.9 3.5-.9 1.2 0 2.5.7 3.4 1.8-3 1.6-2.5 5.9.1 7.5z"/></svg><span><span class="s-small">حمّله من</span><span class="s-big">App Store</span></span></a>
        <a href="#" class="store"><svg viewBox="0 0 24 24"><path fill="#14a800" d="M3.6 2.3 13 11.7l-2.6 2.6L3.6 2.3z"/><path fill="#60a5fa" d="M3.6 21.7 10.4 9.7 13 12.3 3.6 21.7z"/><path fill="#fbbf24" d="m16.2 8.9 3.1 1.8c1 .6 1 1.9 0 2.5l-3.1 1.8-2.8-2.8 2.8-3.3z"/></svg><span><span class="s-small">حمّله من</span><span class="s-big">Google Play</span></span></a>
    </div>
</div></div></section>

<footer id="contact"><div class="container">
    <div class="foot-grid">
        <div><a href="#" class="brand"><span class="mark">أ</span> أنيس</a><p style="margin-top:12px;max-width:340px;color:#9fb3a8;font-size:14px">ذاكر مع ناس، مش لوحدك. المساحة، والجلسة، والرفيق — في تطبيق واحد.</p></div>
        <div style="display:flex;flex-direction:column;gap:8px;font-size:14px">
            <span>✉️ <a href="mailto:amralaa70009@gmail.com">amralaa70009@gmail.com</a></span>
            <span dir="ltr" style="text-align:right">📞 <a href="tel:+201011577033">+20 101 157 7033</a></span>
            <span>📍 الجيزة، جمهورية مصر العربية</span>
            <span>🌐 <a href="http://www.anis-app.com" target="_blank" rel="noopener">www.anis-app.com</a></span>
        </div>
        <nav class="foot-links">
            <a href="#why">ليه أنيس</a><a href="#plans">الأسعار</a><a href="https://sites.google.com/view/anis-privacy-policy/home" target="_blank" rel="noopener">سياسة الخصوصية</a>
        </nav>
    </div>
    <div class="copy">© {{ date('Y') }} أنيس — جميع الحقوق محفوظة.</div>
</div></footer>

<div class="sticky-dl"><div><b>حمّل تطبيق أنيس</b><small>ذاكر مع ناس، مش لوحدك</small></div><a href="#download" class="btn btn-primary" style="padding:11px 22px">تحميل</a></div>
<script>
    const io=new IntersectionObserver((e)=>{e.forEach(x=>{if(x.isIntersecting){x.target.classList.add('in');io.unobserve(x.target);}});},{threshold:0.12});
    document.querySelectorAll('.reveal').forEach(el=>io.observe(el));
</script>
</body>
</html>
