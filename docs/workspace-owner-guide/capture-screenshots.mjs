import fs from 'node:fs/promises';
import path from 'node:path';
import { chromium } from '/Users/eng.amralaa/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright/index.mjs';

const rootDir = '/Users/eng.amralaa/StudioProjects/anis';
const screenshotDir = path.join(rootDir, 'docs/workspace-owner-guide/screenshots');
const baseUrl = 'http://127.0.0.1:8000';

const pages = [
  { slug: 'daily-attendance', url: '/workspace/visits', label: 'الحضور اليومي' },
  { slug: 'visitors', url: '/workspace/clients', label: 'الزوار' },
  { slug: 'sessions', url: '/workspace/sessions', label: 'الجلسات العامة' },
  { slug: 'education-management', url: '/workspace/education', label: 'إدارة التعليم' },
  { slug: 'private-sessions', url: '/workspace/private-sessions', label: 'الجلسات الخاصة' },
  { slug: 'private-session-create', url: '/workspace/private-sessions/create', label: 'إنشاء جلسة خاصة' },
  { slug: 'subscriptions', url: '/workspace/subscriptions', label: 'الاشتراكات الخاصة' },
  { slug: 'rooms', url: '/workspace/rooms', label: 'حجوزات الغرف' },
  { slug: 'notifications', url: '/workspace/notifications', label: 'الإشعارات' },
  { slug: 'settings', url: '/workspace/settings', label: 'الإعدادات' },
];

await fs.mkdir(screenshotDir, { recursive: true });

const browser = await chromium.launch({
  executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
  headless: true,
  args: ['--no-sandbox', '--disable-dev-shm-usage'],
});

try {
  const context = await browser.newContext({
    viewport: { width: 1440, height: 1100 },
    deviceScaleFactor: 1,
    locale: 'ar-EG',
  });
  const page = await context.newPage();

  await page.goto(`${baseUrl}/workspace/login`, { waitUntil: 'domcontentloaded' });
  await page.fill('input[name="phone_number"]', '01200000002');
  await page.fill('input[name="password"]', 'owner123');
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 15000 }).catch(() => null),
    page.click('button[type="submit"]'),
  ]);

  if (page.url().includes('/workspace/login')) {
    throw new Error('Workspace owner login failed using seed credentials 01200000002 / owner123.');
  }

  const captured = [];

  for (const item of pages) {
    await page.goto(`${baseUrl}${item.url}`, { waitUntil: 'domcontentloaded' });
    await page.waitForTimeout(700);
    await page.evaluate(() => window.scrollTo(0, 0));

    const file = path.join(screenshotDir, `${item.slug}.png`);
    await page.screenshot({ path: file, fullPage: false });

    captured.push({
      label: item.label,
      url: `${baseUrl}${item.url}`,
      file,
    });
  }

  console.log(JSON.stringify(captured, null, 2));
} finally {
  await browser.close();
}
