import fs from 'node:fs/promises';
import path from 'node:path';
import { execFile } from 'node:child_process';
import { promisify } from 'node:util';
import { chromium } from '/Users/eng.amralaa/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright/index.mjs';

const execFileAsync = promisify(execFile);

const rootDir = path.resolve(import.meta.dirname);
const buildDir = path.join(rootDir, 'video-build');
const screenshotsDir = path.join(rootDir, 'screenshots');
const outputPath = path.join(rootDir, 'workspace-owner-promo.mp4');

const scenes = [
  {
    key: 'qr',
    image: 'daily-attendance.png',
    title: 'وفر وقت الاستقبال بالـ QR',
    eyebrow: 'دخول وخروج أسرع',
    body: 'مع أنيس، الزائر يدخل ويخرج بسرعة باستخدام QR الخاص بمساحتك، وكل زيارة تتسجل تلقائيًا في الداشبورد بدون زحمة عند الاستقبال.',
    bullets: [
      'دخول وخروج سريع بالـ QR',
      'تسجيل يدوي لأي زائر غير مسجل',
      'متابعة الموجودين داخل المكان الآن',
    ],
  },
  {
    key: 'manage',
    image: 'rooms.png',
    title: 'إدارة الحضور والغرف والدخل',
    eyebrow: 'كل شيء من لوحة واحدة',
    body: 'تابع الحضور والدخل من أي مكان، أضف كل غرفة بإمكانياتها وسعرها، وعدّل الحجوزات بسهولة. وأرسل إشعارات لكل الزوار أو لمجموعة محددة.',
    bullets: [
      'غرف بإمكانيات وأسعار واضحة',
      'حجوزات وإعادة جدولة',
      'إشعارات وتنبيهات قبل انتهاء الاشتراكات',
    ],
  },
  {
    key: 'growth',
    image: 'settings.png',
    title: 'خلّي مكانك يجيب زوار أكثر',
    eyebrow: 'ظهور أفضل في التطبيق',
    body: 'اضبط بيانات المكان، حدد الموقع، واختر المميزات وارفع صور حقيقية. كل ما كان شكل المساحة أوضح، كل ما زادت فرصة إن الزائر يثق ويزورك.',
    bullets: [
      'وصف واضح وصور جذابة',
      'موقع دقيق على الخريطة',
      'إعدادات قوية تعني فرصة أكبر لزوار أكثر',
    ],
  },
  {
    key: 'outro',
    title: 'أنيس',
    eyebrow: 'مساحة عملك أسهل وأوضح',
    body: 'أنيس — إدارة أسهل لمساحة عملك. وفر وقتك، ونظّم زوارك، واجذب عملاء أكثر.',
    bullets: [
      'QR للحضور',
      'لوحة متابعة للدخل والزوار',
      'إشعارات ذكية للزوار',
    ],
  },
];

function escapeHtml(value) {
  return String(value)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}

function renderScene(scene, index) {
  const imageMarkup = scene.image
    ? `<div class="shot-wrap"><img src="${path.join(screenshotsDir, scene.image)}" alt=""></div>`
    : `<div class="brand-card"><div class="brand">أنيس</div><div class="brand-sub">Workspace Portal</div></div>`;

  return `<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  * { box-sizing: border-box; }
  body {
    margin: 0;
    width: 1920px;
    height: 1080px;
    overflow: hidden;
    font-family: "SF Arabic", "Geeza Pro", "Arial", sans-serif;
    color: #f8fff9;
    background:
      radial-gradient(circle at 12% 14%, rgba(46, 204, 113, .34), transparent 28%),
      radial-gradient(circle at 84% 18%, rgba(20, 184, 166, .22), transparent 32%),
      linear-gradient(135deg, #06150d 0%, #0b2415 48%, #021009 100%);
  }
  .stage {
    position: relative;
    width: 100%;
    height: 100%;
    padding: 74px 82px;
    display: grid;
    grid-template-columns: 0.92fr 1.08fr;
    gap: 54px;
    align-items: center;
  }
  .stage::before {
    content: "";
    position: absolute;
    inset: 28px;
    border: 1px solid rgba(106, 255, 164, .16);
    border-radius: 42px;
    box-shadow: inset 0 0 90px rgba(67, 255, 142, .08);
  }
  .copy, .visual { position: relative; z-index: 1; }
  .copy {
    padding: 62px 58px;
    border-radius: 38px;
    background: rgba(5, 24, 13, .78);
    border: 1px solid rgba(122, 255, 173, .22);
    box-shadow: 0 32px 100px rgba(0, 0, 0, .35);
  }
  .scene-number {
    display: inline-flex;
    min-width: 58px;
    height: 58px;
    align-items: center;
    justify-content: center;
    border-radius: 999px;
    background: #17c964;
    color: #03130a;
    font-size: 28px;
    font-weight: 900;
    margin-bottom: 30px;
    box-shadow: 0 18px 42px rgba(23, 201, 100, .35);
  }
  .eyebrow {
    color: #8af7b5;
    font-weight: 900;
    font-size: 34px;
    margin-bottom: 18px;
  }
  h1 {
    margin: 0 0 34px;
    font-size: 68px;
    line-height: 1.16;
    letter-spacing: -1.5px;
  }
  .body {
    color: #dceee3;
    font-size: 34px;
    line-height: 1.78;
    margin-bottom: 36px;
  }
  ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 18px;
  }
  li {
    display: flex;
    gap: 16px;
    align-items: center;
    font-size: 31px;
    font-weight: 800;
    color: #f6fff8;
    padding: 18px 22px;
    border-radius: 22px;
    background: rgba(255, 255, 255, .075);
    border: 1px solid rgba(154, 255, 189, .13);
  }
  li::before {
    content: "✓";
    flex: 0 0 36px;
    height: 36px;
    border-radius: 12px;
    background: #28e074;
    color: #052011;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 1000;
  }
  .visual {
    height: 860px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .shot-wrap {
    width: 100%;
    height: 100%;
    padding: 24px;
    border-radius: 42px;
    background: linear-gradient(145deg, rgba(255,255,255,.96), rgba(236,255,244,.86));
    border: 1px solid rgba(151, 255, 185, .28);
    box-shadow: 0 48px 110px rgba(0,0,0,.42);
  }
  .shot-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top right;
    border-radius: 30px;
    display: block;
  }
  .brand-card {
    width: 100%;
    height: 100%;
    border-radius: 48px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background:
      radial-gradient(circle at 50% 32%, rgba(46, 204, 113, .42), transparent 28%),
      linear-gradient(145deg, rgba(18, 87, 46, .78), rgba(3, 23, 12, .96));
    border: 1px solid rgba(132, 255, 176, .22);
    box-shadow: 0 48px 110px rgba(0,0,0,.42);
  }
  .brand {
    font-size: 150px;
    font-weight: 1000;
    letter-spacing: -4px;
  }
  .brand-sub {
    margin-top: 22px;
    font-size: 44px;
    color: #9cf8bd;
    font-weight: 900;
  }
</style>
</head>
<body>
  <main class="stage">
    <section class="copy">
      <div class="scene-number">${index + 1}</div>
      <div class="eyebrow">${escapeHtml(scene.eyebrow)}</div>
      <h1>${escapeHtml(scene.title)}</h1>
      <div class="body">${escapeHtml(scene.body)}</div>
      <ul>${scene.bullets.map((item) => `<li>${escapeHtml(item)}</li>`).join('')}</ul>
    </section>
    <section class="visual">${imageMarkup}</section>
  </main>
</body>
</html>`;
}

async function run() {
  await fs.rm(buildDir, { recursive: true, force: true });
  await fs.mkdir(buildDir, { recursive: true });

  const browser = await chromium.launch({
    executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
  });
  const page = await browser.newPage({ viewport: { width: 1920, height: 1080 }, deviceScaleFactor: 1 });

  const imagePaths = [];
  for (const [index, scene] of scenes.entries()) {
    const htmlPath = path.join(buildDir, `${String(index + 1).padStart(2, '0')}-${scene.key}.html`);
    const pngPath = path.join(buildDir, `${String(index + 1).padStart(2, '0')}-${scene.key}.png`);
    await fs.writeFile(htmlPath, renderScene(scene, index), 'utf8');
    await page.goto(`file://${htmlPath}`, { waitUntil: 'networkidle' });
    await page.screenshot({ path: pngPath, fullPage: false });
    imagePaths.push(pngPath);
  }
  await browser.close();

  const audioPaths = [];
  const audioInputs = [];
  for (const [index, scene] of scenes.entries()) {
    const audioPath = path.join(buildDir, `${String(index + 1).padStart(2, '0')}-${scene.key}.aiff`);
    await execFileAsync('say', ['-v', 'Majed', '-r', '215', '-o', audioPath, scene.body]);
    audioPaths.push(audioPath);
    audioInputs.push(`file '${audioPath}'`);
    const silencePath = path.join(buildDir, `${String(index + 1).padStart(2, '0')}-${scene.key}-silence.aiff`);
    await execFileAsync('ffmpeg', [
      '-y',
      '-f', 'lavfi',
      '-i', 'anullsrc=channel_layout=stereo:sample_rate=44100',
      '-t', index === scenes.length - 1 ? '1.2' : '0.8',
      '-c:a', 'pcm_s16be',
      silencePath,
    ]);
    audioInputs.push(`file '${silencePath}'`);
  }

  const audioListPath = path.join(buildDir, 'audio-inputs.txt');
  const narrationPath = path.join(buildDir, 'narration.aiff');
  await fs.writeFile(audioListPath, audioInputs.join('\n'), 'utf8');
  await execFileAsync('ffmpeg', ['-y', '-f', 'concat', '-safe', '0', '-i', audioListPath, '-c', 'copy', narrationPath]);

  const { stdout } = await execFileAsync('ffprobe', [
    '-v', 'error',
    '-show_entries', 'format=duration',
    '-of', 'default=noprint_wrappers=1:nokey=1',
    narrationPath,
  ]);
  const totalDuration = Number(stdout.trim());
  const sceneDuration = Math.max(8, totalDuration / scenes.length);

  const videoInputs = [];
  for (const imagePath of imagePaths) {
    videoInputs.push(`file '${imagePath}'`);
    videoInputs.push(`duration ${sceneDuration.toFixed(3)}`);
  }
  videoInputs.push(`file '${imagePaths.at(-1)}'`);
  const videoListPath = path.join(buildDir, 'video-inputs.txt');
  const silentVideoPath = path.join(buildDir, 'silent-video.mp4');
  await fs.writeFile(videoListPath, videoInputs.join('\n'), 'utf8');

  await execFileAsync('ffmpeg', [
    '-y',
    '-f', 'concat',
    '-safe', '0',
    '-i', videoListPath,
    '-vf', 'scale=1920:1080,fps=30,format=yuv420p',
    '-c:v', 'libx264',
    '-preset', 'medium',
    '-crf', '19',
    silentVideoPath,
  ]);

  await execFileAsync('ffmpeg', [
    '-y',
    '-i', silentVideoPath,
    '-i', narrationPath,
    '-c:v', 'copy',
    '-c:a', 'aac',
    '-b:a', '160k',
    '-shortest',
    outputPath,
  ]);

  console.log(outputPath);
}

run().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
