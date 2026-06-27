<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أنيس | مساحات المذاكرة بين يديك</title>
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
        .btn-outline{background:#fff;color:var(--green-ink);border-color:var(--line)}
        .btn-outline:hover{background:var(--green-soft); border-color:var(--green)}
        .reveal{opacity:0;transform:translateY(28px);transition:.7s cubic-bezier(.2,.7,.2,1)}
        .reveal.in{opacity:1;transform:none}
        .tag{display:inline-block;color:var(--green-dark);font-weight:800;letter-spacing:1.5px;font-size:13px;text-transform:uppercase}

        .topbar{background:var(--ink);color:#dff3e8;text-align:center;font-size:14px;padding:10px 16px;font-weight:500}
        .topbar b{color:var(--gold)}

        header.nav{position:sticky;top:0;z-index:60;background:rgba(255,255,255,.9);backdrop-filter:blur(14px);border-bottom:1px solid var(--line)}
        .nav-inner{display:flex;align-items:center;justify-content:space-between;height:78px}
        .brand{display:flex;align-items:center;gap:11px;font-weight:900;font-size:26px;color:var(--ink)}
        .brand .mark{width:46px;height:46px;border-radius:14px;background:linear-gradient(135deg,var(--green),var(--green-dark));color:#fff;display:grid;place-items:center;font-size:24px;font-weight:900}
        
        /* HERO */
        .hero{padding:120px 0 100px;background:radial-gradient(900px 420px at 50% 0%,var(--green-soft),transparent); text-align: center;}
        .hero-content{max-width: 760px; margin: 0 auto;}
        .pill{display:inline-flex;align-items:center;gap:8px;background:#fff;border:1px solid var(--line);color:var(--green-dark);font-weight:700;padding:9px 18px;border-radius:999px;font-size:14px;margin-bottom:28px;box-shadow:0 6px 18px rgba(16,138,0,.06)}
        .pill .dot{width:9px;height:9px;border-radius:50%;background:var(--green);box-shadow:0 0 0 4px rgba(20,168,0,.18)}
        .hero h1{font-size:68px;font-weight:900;line-height:1.2;letter-spacing:-1px;margin-bottom:24px}
        .hero h1 .hl{color:var(--green);position:relative;white-space:nowrap}
        .hero h1 .hl::after{content:"";position:absolute;left:0;right:0;bottom:6px;height:14px;background:rgba(20,168,0,.16);z-index:-1;border-radius:6px}
        .hero p.lead{font-size:22px;color:var(--muted);margin:0 auto 32px;max-width:640px}
        @media(max-width:900px){.hero h1{font-size:42px}}

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

        /* PLANS */
        .spaces{background:var(--cream)}
        .plans{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;align-items:stretch}
        @media(max-width:820px){.plans{grid-template-columns:1fr;max-width:430px;margin:0 auto}}
        .plan{background:#fff;border:1px solid var(--line);border-radius:var(--radius);padding:34px 28px;display:flex;flex-direction:column}
        .plan.featured{border:2px solid var(--green);box-shadow:var(--shadow-lg);position:relative}
        .plan.featured .ribbon{position:absolute;top:-14px;left:50%;transform:translateX(-50%);background:var(--green);color:#fff;font-weight:800;font-size:13px;padding:6px 18px;border-radius:999px}
        .plan h3{font-size:21px;font-weight:900}
        .plan .price{font-size:44px;font-weight:900;color:var(--green-dark);margin:10px 0 2px}
        .plan .price small{font-size:16px;color:var(--muted);font-weight:600}
        .plan ul{list-style:none;margin:18px 0 26px;display:flex;flex-direction:column;gap:12px;flex-grow:1}
        .plan li{display:flex;gap:9px;color:var(--muted);font-size:15px}
        .plan li::before{content:"✓";color:var(--green);font-weight:900}
        .plan .btn{width:100%;margin-top:auto}

        footer{background:var(--ink);color:#cdd9d2;padding:40px 0;text-align:center}
        .foot-links{margin-top:16px}
        .foot-links a{margin:0 10px;color:#8ea197}
        .foot-links a:hover{color:var(--gold)}
    </style>
</head>
<body>

<div class="topbar">🎓 <b>اشتراك واحد</b> يفتح لك كل مساحات المذاكرة الشريكة في مصر — حمّل أنيس الآن.</div>

<header class="nav"><div class="container nav-inner">
    <div class="brand"><span class="mark">أ</span> أنيس</div>
    <div>
        @auth
            <a href="{{ route('workspace.settings.edit') }}" class="btn btn-outline" style="padding: 10px 24px;">لوحة التحكم</a>
        @else
            <a href="{{ route('workspace.login') }}" class="btn btn-outline" style="padding: 10px 24px;">بوابة الشركاء</a>
        @endauth
    </div>
</div></header>

<!-- HERO -->
<section class="hero"><div class="container">
    <div class="hero-content reveal in">
        <span class="pill"><span class="dot"></span> شبكة المذاكرة الأكبر في مصر</span>
        <h1>اشتراك واحد.<br><span class="hl">كل مساحات المذاكرة.</span></h1>
        <p class="lead">بدل ما تدفع في كل مكان، اشترك في أنيس وادخل أي مساحة شريكة، انضم لجلسات المذاكرة، ولاقِ رفيقك المناسب بكل سهولة.</p>
        <div style="display:flex; justify-content:center; gap:16px; margin-top:32px;">
            <a href="https://play.google.com/store/apps/details?id=com.anisByAmrAlaa.anis" target="_blank" class="btn btn-primary" style="padding: 18px 40px; font-size: 18px;">حمّل التطبيق الآن</a>
        </div>
    </div>
</div></section>

<!-- FEATURES -->
<section id="features"><div class="container">
    <div class="sec-head reveal"><div class="tag">مميزات أنيس</div><h2>كل ما تحتاجه للمذاكرة في مكان واحد</h2><p>وفر وقتك ومجهودك مع مميزات أنيس المصممة للطلاب</p></div>
    <div class="features">
        <div class="feature reveal"><div class="ic">📱</div><h3>دخول سريع بالـ QR</h3><p>امسح الكود عند الدخول والخروج. التطبيق هيحسب وقتك ويخصمه من رصيدك تلقائياً بدون أي تسجيل يدوي.</p></div>
        <div class="feature reveal"><div class="ic">👥</div><h3>رفيق المذاكرة</h3><p>بنطابقك مع طلاب من نفس جامعتك وتخصصك عشان تذاكروا مع بعض وتشجعوا بعض.</p></div>
        <div class="feature reveal"><div class="ic">📅</div><h3>جلسات حية</h3><p>انضم لجلسات مذاكرة جماعية، اسأل، وتفاعل مع طلاب زيك في نفس المادة.</p></div>
        <div class="feature reveal"><div class="ic">💳</div><h3>محفظة واحدة للكل</h3><p>رصيد ساعاتك صالح للاستخدام في أي مساحة مذاكرة شريكة في شبكة أنيس. مش محتاج تدفع كاش تاني.</p></div>
    </div>
</div></section>

<!-- PLANS -->
<section id="plans" class="spaces"><div class="container">
    <div class="sec-head reveal"><div class="tag">الأسعار</div><h2>باقات المذاكرة</h2><p>اختر الباقة اللي تناسب وقتك. أسعار بسيطة، وبدون التزامات معقدة.</p></div>
    <div class="plans reveal">
        @forelse($plans ?? [] as $plan)
            <div class="plan {{ $plan->tier->value === 'SILVER' ? 'featured' : '' }}">
                @if($plan->tier->value === 'SILVER')
                    <span class="ribbon">الأكثر شعبية</span>
                @endif
                <h3>{{ $plan->name }}</h3>
                <div class="price">
                    {{ number_format($plan->price_cents / 100, 0) }}
                    <small>ج.م / @if($plan->duration_days == 30) شهر @else {{ $plan->duration_days }} يوم @endif</small>
                </div>
                <ul>
                    @if($plan->tier->value === 'FREE')
                        <li>استكشاف المساحات والجلسات</li>
                        <li>الانضمام لجلسة واحدة مجانية</li>
                    @else
                        <li>رصيد: {{ number_format($plan->included_minutes / 60, 0) }} ساعة</li>
                        <li>دخول جميع المساحات الشريكة</li>
                        <li>إنشاء والانضمام لجلسات غير محدودة</li>
                        <li>مطابقة رفقاء المذاكرة</li>
                    @endif
                </ul>
                <a href="https://play.google.com/store/apps/details?id=com.anisByAmrAlaa.anis" target="_blank" class="btn {{ $plan->tier->value === 'SILVER' ? 'btn-primary' : 'btn-outline' }}">
                    @if($plan->tier->value === 'FREE') ابدأ مجانًا @else اشترك الآن @endif
                </a>
            </div>
        @empty
            <div class="plan"><h3>باقة التجربة</h3><div class="price">0 <small>ج.م</small></div><ul><li>استكشاف المساحات</li><li>الانضمام لجلسة واحدة</li></ul><a href="https://play.google.com/store/apps/details?id=com.anisByAmrAlaa.anis" target="_blank" class="btn btn-outline">ابدأ مجانًا</a></div>
            <div class="plan featured"><span class="ribbon">الأكثر شعبية</span><h3>الباقة الفضية</h3><div class="price">199 <small>ج.م / شهر</small></div><ul><li>رصيد 50 ساعة</li><li>دخول جميع المساحات الشريكة</li><li>مطابقة رفقاء المذاكرة</li></ul><a href="https://play.google.com/store/apps/details?id=com.anisByAmrAlaa.anis" target="_blank" class="btn btn-primary">اشترك الآن</a></div>
        @endforelse
    </div>
</div></section>

<!-- PARTNER CTA -->
<section style="border-top: 1px solid var(--line); padding: 80px 0;"><div class="container"><div style="background:linear-gradient(135deg,var(--ink),#0c2516); color:#fff; text-align:center; padding:60px 30px; border-radius:24px;" class="reveal">
    <div class="tag" style="color:var(--green); margin-bottom: 12px;">شركاء أنيس</div>
    <h2 style="font-size:32px; font-weight:900; margin-bottom:16px; color:#fff;">عندك مساحة عمل أو مركز تعليمي؟</h2>
    <p style="font-size:18px; opacity:0.9; margin-bottom:32px; max-width:640px; margin-left:auto; margin-right:auto; color:#dff3e8;">انضم لشبكة أنيس، ضاعف عدد زوارك، ودير حجوزاتك واشتراكات عملائك من لوحة تحكم ذكية بالكامل.</p>
    <a href="{{ route('workspace.register') }}" class="btn btn-primary">سجل مساحتك كشريك معنا</a>
</div></div></section>

<footer>
    <p>© {{ date('Y') }} أنيس. جميع الحقوق محفوظة.</p>
    <div class="foot-links">
        <a href="mailto:amralaa70009@gmail.com">الدعم الفني</a> • 
        <a href="https://sites.google.com/view/anis-privacy-policy/home" target="_blank">سياسة الخصوصية</a>
    </div>
</footer>

<script>
    const io=new IntersectionObserver((e)=>{e.forEach(x=>{if(x.isIntersecting){x.target.classList.add('in');io.unobserve(x.target);}});},{threshold:0.12});
    document.querySelectorAll('.reveal').forEach(el=>io.observe(el));
</script>
</body>
</html>
