import 'package:flutter/material.dart';

class DCardTheme {
  DCardTheme._();

  static CardTheme lightCardTheme = CardTheme(
    color: Colors.white,
    elevation: 8.0, // Higher shadow effect
    shape: RoundedRectangleBorder(
      borderRadius: BorderRadius.circular(16.0), // More rounded corners
    ),
  );

  static CardTheme darkCardTheme = CardTheme(
    color: Colors.white,
    elevation: 8.0, // Higher shadow effect
    shape: RoundedRectangleBorder(
      borderRadius: BorderRadius.circular(16.0), // More rounded corners
    ),
  );
}
