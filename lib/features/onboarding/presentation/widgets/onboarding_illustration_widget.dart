import 'package:flutter/material.dart';
import 'package:anis/core/constants/colors.dart';

/// Pure-Flutter illustrated panels for each onboarding page.
class OnboardingIllustration extends StatelessWidget {
  final int pageIndex;
  const OnboardingIllustration({super.key, required this.pageIndex});

  @override
  Widget build(BuildContext context) {
    switch (pageIndex) {
      case 0:  return const _WorkspacePassIllustration();
      case 1:  return const _StudySessionsIllustration();
      default: return const _FindBuddyIllustration();
    }
  }
}

// ── Page 0 — One Pass. Every Space. ──────────────────────────────────────────
// A building with a subscription key badge + location pin.

class _WorkspacePassIllustration extends StatelessWidget {
  const _WorkspacePassIllustration();
  @override
  Widget build(BuildContext context) =>
      CustomPaint(size: const Size(double.infinity, double.infinity), painter: _WorkspacePassPainter());
}

class _WorkspacePassPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final cx = size.width / 2;
    final cy = size.height / 2;

    // Background glow
    canvas.drawCircle(
      Offset(cx, cy - 10),
      size.width * 0.44,
      Paint()..color = ColorRes.anisGreen.withValues(alpha: 0.09)
             ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 60),
    );

    // ── Building body ──
    final bldW = size.width * 0.46;
    final bldH = size.height * 0.50;
    final bldLeft = cx - bldW / 2;
    final bldTop  = cy - bldH / 2 - 10;

    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(bldLeft, bldTop, bldW, bldH),
        const Radius.circular(14),
      ),
      Paint()..color = ColorRes.white
             ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 16),
    );
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(bldLeft, bldTop, bldW, bldH),
        const Radius.circular(14),
      ),
      Paint()..color = ColorRes.anisCardBg,
    );

    // ── Green roof strip ──
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(bldLeft, bldTop, bldW, 10),
        const Radius.circular(14),
      ),
      Paint()..color = ColorRes.anisGreen,
    );

    // ── Windows grid ──
    final winPaint = Paint()..color = ColorRes.anisGreen.withValues(alpha: 0.22);
    final winActivePaint = Paint()..color = ColorRes.anisGreen.withValues(alpha: 0.80);
    const cols = 3;
    const rows = 3;
    final winW = bldW * 0.18;
    final winH = bldH * 0.12;
    final hGap = (bldW - cols * winW) / (cols + 1);
    final vGap = (bldH * 0.75 - rows * winH) / (rows + 1);
    final gridTop = bldTop + bldH * 0.18;

    for (var r = 0; r < rows; r++) {
      for (var c = 0; c < cols; c++) {
        final x = bldLeft + hGap + c * (winW + hGap);
        final y = gridTop + vGap + r * (winH + vGap);
        final isActive = (r == 0 && c == 1) || (r == 1 && c == 0) || (r == 2 && c == 2);
        canvas.drawRRect(
          RRect.fromRectAndRadius(Rect.fromLTWH(x, y, winW, winH), const Radius.circular(3)),
          isActive ? winActivePaint : winPaint,
        );
      }
    }

    // ── Door ──
    final doorW = bldW * 0.20;
    final doorH = bldH * 0.20;
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(cx - doorW / 2, bldTop + bldH - doorH, doorW, doorH),
        const Radius.circular(4),
      ),
      Paint()..color = ColorRes.anisGreen,
    );

    // ── Subscription badge ──
    final badgeCx = bldLeft + bldW + 8;
    final badgeCy = bldTop + 28.0;
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromCenter(center: Offset(badgeCx, badgeCy), width: 68, height: 30),
        const Radius.circular(15),
      ),
      Paint()..color = ColorRes.anisGold,
    );
    _drawText(canvas, '✓ Pass', Offset(badgeCx, badgeCy),
        const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w800));

    // ── Location pin ──
    final pin = Paint()..color = ColorRes.anisGreen;
    canvas.drawCircle(Offset(cx, bldTop - 22), 11, pin);
    canvas.drawCircle(Offset(cx, bldTop - 22), 5,
        Paint()..color = ColorRes.white);
    final path = Path()
      ..moveTo(cx - 11, bldTop - 22)
      ..lineTo(cx + 11, bldTop - 22)
      ..lineTo(cx, bldTop - 5);
    canvas.drawPath(path, pin);
  }

  void _drawText(Canvas canvas, String text, Offset centre, TextStyle style) {
    final tp = TextPainter(
      text: TextSpan(text: text, style: style),
      textDirection: TextDirection.ltr,
    )..layout();
    tp.paint(canvas, centre - Offset(tp.width / 2, tp.height / 2));
  }

  @override
  bool shouldRepaint(_WorkspacePassPainter old) => false;
}

// ── Page 1 — Join Study Sessions ─────────────────────────────────────────────
// A weekly schedule card with coloured time-slot blocks and join buttons.

class _StudySessionsIllustration extends StatelessWidget {
  const _StudySessionsIllustration();
  @override
  Widget build(BuildContext context) =>
      CustomPaint(size: const Size(double.infinity, double.infinity), painter: _StudySessionsPainter());
}

class _StudySessionsPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final cx = size.width / 2;
    final cy = size.height / 2;

    // Background glow
    canvas.drawCircle(
      Offset(cx, cy),
      size.width * 0.42,
      Paint()..color = ColorRes.anisButtonGreen.withValues(alpha: 0.09)
             ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 55),
    );

    // ── Schedule card ──
    final cardW = size.width * 0.68;
    final cardH = size.height * 0.58;
    final cardLeft = cx - cardW / 2;
    final cardTop  = cy - cardH / 2;

    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(cardLeft, cardTop, cardW, cardH),
        const Radius.circular(16),
      ),
      Paint()..color = ColorRes.anisNavy.withValues(alpha: 0.08)
             ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 14),
    );
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(cardLeft, cardTop, cardW, cardH),
        const Radius.circular(16),
      ),
      Paint()..color = ColorRes.white,
    );
    // Header
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(cardLeft, cardTop, cardW, 34),
        const Radius.circular(16),
      ),
      Paint()..color = ColorRes.anisGreen,
    );
    _drawText(canvas, 'Today\'s Sessions', Offset(cx, cardTop + 17),
        const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w700));

    // Session rows
    final sessions = [
      ('📐  Physics', ColorRes.anisTagBlue,    ColorRes.anisTagBlueTxt,  '2:00 PM', true),
      ('📊  Calculus', ColorRes.anisTagYellow, ColorRes.anisTagYellowTxt,'4:30 PM', false),
      ('🧠  Psych',   ColorRes.anisTagPink,   ColorRes.anisTagPinkTxt,  '7:00 PM', false),
    ];

    final rowH = (cardH - 34) / sessions.length;
    for (var i = 0; i < sessions.length; i++) {
      final (label, bg, fg, time, isJoining) = sessions[i];
      final rowTop = cardTop + 34 + i * rowH;

      // Divider
      if (i > 0) {
        canvas.drawLine(
          Offset(cardLeft + 12, rowTop),
          Offset(cardLeft + cardW - 12, rowTop),
          Paint()..color = ColorRes.anisLine..strokeWidth = 1,
        );
      }

      // Tag
      canvas.drawRRect(
        RRect.fromRectAndRadius(
          Rect.fromLTWH(cardLeft + 10, rowTop + 10, cardW * 0.40, rowH - 20),
          const Radius.circular(6),
        ),
        Paint()..color = bg,
      );
      _drawText(canvas, label, Offset(cardLeft + 10 + cardW * 0.20, rowTop + rowH / 2),
          TextStyle(color: fg, fontSize: 10, fontWeight: FontWeight.w700));

      // Time
      _drawText(canvas, time, Offset(cardLeft + cardW * 0.72, rowTop + rowH / 2),
          TextStyle(color: ColorRes.anisTextMuted, fontSize: 10));

      // Join button (only first session)
      if (isJoining) {
        canvas.drawRRect(
          RRect.fromRectAndRadius(
            Rect.fromLTWH(cardLeft + cardW - 48, rowTop + rowH / 2 - 11, 38, 22),
            const Radius.circular(11),
          ),
          Paint()..color = ColorRes.anisGreen,
        );
        _drawText(canvas, 'Join', Offset(cardLeft + cardW - 29, rowTop + rowH / 2),
            const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.w700));
      }
    }

    // ── Floating user count badge ──
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromCenter(
          center: Offset(cx + size.width * 0.30, cardTop - 16),
          width: 64, height: 26,
        ),
        const Radius.circular(13),
      ),
      Paint()..color = ColorRes.anisTagGreen,
    );
    _drawText(canvas, '👥  +12', Offset(cx + size.width * 0.30, cardTop - 16),
        TextStyle(color: ColorRes.anisTagGreenTxt, fontSize: 11, fontWeight: FontWeight.w700));
  }

  void _drawText(Canvas canvas, String text, Offset centre, TextStyle style) {
    final tp = TextPainter(
      text: TextSpan(text: text, style: style),
      textDirection: TextDirection.ltr,
    )..layout();
    tp.paint(canvas, centre - Offset(tp.width / 2, tp.height / 2));
  }

  @override
  bool shouldRepaint(_StudySessionsPainter old) => false;
}

// ── Page 2 — Find Your Buddy ──────────────────────────────────────────────────
// Connected student circles with a "✓ Match" badge — cleaner version.

class _FindBuddyIllustration extends StatelessWidget {
  const _FindBuddyIllustration();
  @override
  Widget build(BuildContext context) =>
      CustomPaint(size: const Size(double.infinity, double.infinity), painter: _FindBuddyPainter());
}

class _FindBuddyPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final cx = size.width / 2;
    final cy = size.height / 2;

    // Background glow
    canvas.drawCircle(
      Offset(cx, cy),
      size.width * 0.45,
      Paint()..color = ColorRes.anisGold.withValues(alpha: 0.08)
             ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 65),
    );

    // Avatar positions
    final centre = Offset(cx, cy);
    final top    = Offset(cx, cy - size.height * 0.26);
    final left   = Offset(cx - size.width * 0.30, cy + size.height * 0.10);
    final right  = Offset(cx + size.width * 0.30, cy + size.height * 0.10);

    // Connection lines
    final linePaint = Paint()
      ..color = ColorRes.anisGreen.withValues(alpha: 0.22)
      ..strokeWidth = 2
      ..style = PaintingStyle.stroke
      ..strokeCap = StrokeCap.round;

    for (final end in [top, left, right]) {
      canvas.drawLine(centre, end, linePaint);
    }

    // Avatars
    final avatars = [
      (centre, ColorRes.anisGreen,       '📚', 36.0, true),
      (top,    ColorRes.anisGold,        '🎯', 26.0, false),
      (left,   ColorRes.anisTagBlueTxt,  '👩‍🎓', 26.0, false),
      (right,  ColorRes.anisTagPinkTxt,  '👨‍💻', 26.0, false),
    ];

    for (final (pos, color, emoji, r, isMain) in avatars) {
      // Shadow
      canvas.drawCircle(pos, r + 4,
          Paint()..color = color.withValues(alpha: 0.15)
                 ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 10));
      // White fill
      canvas.drawCircle(pos, r, Paint()..color = ColorRes.white);
      // Ring
      canvas.drawCircle(pos, r,
          Paint()..color = isMain ? ColorRes.anisGreen.withValues(alpha: 0.80) : color.withValues(alpha: 0.30)
                 ..style = PaintingStyle.stroke
                 ..strokeWidth = isMain ? 2.5 : 1.5);
      // Emoji
      final tp = TextPainter(
        text: TextSpan(text: emoji, style: TextStyle(fontSize: r * 0.90)),
        textDirection: TextDirection.ltr,
      )..layout();
      tp.paint(canvas, pos - Offset(tp.width / 2, tp.height / 2));
    }

    // Pulse ring on centre
    canvas.drawCircle(centre, 46,
        Paint()..color = ColorRes.anisGreen.withValues(alpha: 0.12)
               ..style = PaintingStyle.stroke
               ..strokeWidth = 10);

    // ✓ Match badge
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromCenter(
          center: Offset(cx + size.width * 0.26, cy - size.height * 0.24),
          width: 68, height: 26,
        ),
        const Radius.circular(13),
      ),
      Paint()..color = ColorRes.anisGreen,
    );
    final tp2 = TextPainter(
      text: const TextSpan(
        text: '✓  Match',
        style: TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w700),
      ),
      textDirection: TextDirection.ltr,
    )..layout();
    tp2.paint(canvas,
        Offset(cx + size.width * 0.26 - tp2.width / 2,
               cy - size.height * 0.24 - tp2.height / 2));
  }

  @override
  bool shouldRepaint(_FindBuddyPainter old) => false;
}
