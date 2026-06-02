import 'package:anis/generated/l10n.dart';

class Validators {
  // Email validation
  static String? email(String? value) {
    if (value == null || value.isEmpty) {
      return S.current.pleaseEndterValue;
    }

    final emailRegex = RegExp(
      r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$',
    );

    if (!emailRegex.hasMatch(value)) {
      return 'Please enter a valid email address';
    }

    return null;
  }

  // Password validation
  static String? password(String? value) {
    if (value == null || value.isEmpty) {
      return S.current.pleaseEndterValue;
    }

    if (value.length < 6) {
      // return 'Password must be at least 6 characters';
      return S.current.authenticationError;
    }

    return null;
  }

  // Username validation
  static String? username(String? value) {
    if (value == null || value.isEmpty) {
      return S.current.pleaseEndterValue;
    }

    if (value.length < 2) {
      // return 'Username must be at least 2 characters';
      return S.current.authenticationError;

    }

    return null;
  }

  // Required field validation
  static String? required(String? value) {
    if (value == null || value.isEmpty) {
      return S.current.pleaseEndterValue;
    }

    return null;
  }

  // Confirm password validation
  static String? confirmPassword(String? value, String? password) {
    if (value == null || value.isEmpty) {
      return S.current.pleaseEndterValue;
    }

    if (value != password) {
      return 'Passwords do not match';
    }

    return null;
  }

  // Passport number validation
  static String? passportNumber(String? value) {
    if (value == null || value.isEmpty) {
      return S.current.pleaseEndterValue;
    }

    if (value.length < 6) {
      return 'Passport number must be at least 6 characters';
    }

    return null;
  }

  // Phone number validation
  static String? phone(String? value) {
    if (value == null || value.isEmpty) {
      return S.current.pleaseEndterValue;
    }

    final phoneRegex = RegExp(r'^[0-9]{10,15}$');

    if (!phoneRegex.hasMatch(value.replaceAll(RegExp(r'[\s\-()]'), ''))) {
      return 'Please enter a valid phone number';
    }

    return null;
  }

  // OTP validation
  static String? otp(String? value, {int length = 6}) {
    if (value == null || value.isEmpty) {
      return S.current.pleaseEndterValue;
    }

    if (value.length != length) {
      return 'OTP must be $length digits';
    }

    if (!RegExp(r'^[0-9]+$').hasMatch(value)) {
      return 'OTP must contain only numbers';
    }

    return null;
  }
}


