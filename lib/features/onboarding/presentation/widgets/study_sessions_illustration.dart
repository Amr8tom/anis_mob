import 'package:flutter/material.dart';
import 'package:anis/core/constants/colors.dart';

class StudySessionsIllustration extends StatelessWidget {
  const StudySessionsIllustration({super.key});

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
    // Hardcoded text to keep onboarding completely decoupled from translation lookup contexts
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
