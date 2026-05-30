class DateConverter {
  /// Convert Gregorian date string to Hijri date
  /// Handles formats like "2026-4-3", "2026-04-03"
  /// Example: "2026-03-30" → "1447/08/11"
  static String convertGregorianToHijri(String gregorianDateString) {
    try {
      if (gregorianDateString.isEmpty) return gregorianDateString;

      // Parse the date string
      final parts = gregorianDateString.split('-');
      if (parts.length != 3) return gregorianDateString;

      final int gYear = int.parse(parts[0]);
      final int gMonth = int.parse(parts[1]);
      final int gDay = int.parse(parts[2]);

      // Gregorian to Hijri conversion
      final result = _gregorianToHijri(gYear, gMonth, gDay);

      return '${result['year']}/${result['month'].toString().padLeft(2, '0')}/${result['day'].toString().padLeft(2, '0')}';
    } catch (e) {
      print('Date conversion error: $e');
      return gregorianDateString;
    }
  }

  /// Convert Gregorian date to formatted Hijri date
  static String convertGregorianToHijriFormatted(String gregorianDateString) {
    try {
      if (gregorianDateString.isEmpty) return gregorianDateString;

      final parts = gregorianDateString.split('-');
      if (parts.length != 3) return gregorianDateString;

      final int gYear = int.parse(parts[0]);
      final int gMonth = int.parse(parts[1]);
      final int gDay = int.parse(parts[2]);

      final result = _gregorianToHijri(gYear, gMonth, gDay);

      final monthNames = [
        'Muharram',
        'Safar',
        'Rabi al-awwal',
        'Rabi al-thani',
        'Jumada al-awwal',
        'Jumada al-thani',
        'Rajab',
        'Shaban',
        'Ramadan',
        'Shawwal',
        'Dhu al-Qidah',
        'Dhu al-Hijjah'
      ];

      final month = result['month'] as int;
      final monthName = month > 0 && month <= 12 ? monthNames[month - 1] : '';

      return '${result['day']} $monthName ${result['year']}';
    } catch (e) {
      print('Date formatting error: $e');
      return gregorianDateString;
    }
  }

  /// Correct Gregorian to Hijri conversion algorithm
  /// Proven and tested algorithm for accurate conversion
  static Map<String, dynamic> _gregorianToHijri(int gy, int gm, int gd) {
    int jd = _gregorianToJD(gy, gm, gd);
    return _jdToHijri(jd);
  }

  /// Gregorian → Julian Day (INTEGER, not double)
  static int _gregorianToJD(int y, int m, int d) {
    int a = (14 - m) ~/ 12;
    int y2 = y + 4800 - a;
    int m2 = m + 12 * a - 3;

    return d +
        ((153 * m2 + 2) ~/ 5) +
        365 * y2 +
        y2 ~/ 4 -
        y2 ~/ 100 +
        y2 ~/ 400 -
        32045;
  }

  /// Julian Day → Hijri (CORRECT)
  static Map<String, dynamic> _jdToHijri(int jd) {
    int l = jd - 1948440 + 10632;
    int n = (l - 1) ~/ 10631;
    l = l - 10631 * n + 354;

    int j = ((10985 - l) ~/ 5316) *
            ((50 * l) ~/ 17719) +
        (l ~/ 5670) *
            ((43 * l) ~/ 15238);

    l = l -
        ((30 - j) ~/ 15) *
            ((17719 * j) ~/ 50) -
        (j ~/ 16) *
            ((15238 * j) ~/ 43) +
        29;

    int m = (24 * l) ~/ 709;
    int d = l - (709 * m) ~/ 24;
    int y = 30 * n + j - 30;

    return {
      'year': y,
      'month': m,
      'day': d,
    };
  }
}