import 'package:flutter/material.dart';
import '../../core/error/failure.dart';
import 'package:url_launcher/url_launcher.dart';

class Contacts {
  static launchFacebookProfile({required String faceLink}) async {
    String facebookUrl = faceLink;

    if (await canLaunch(facebookUrl)) {
      await launch(facebookUrl);
    } else {
      throw 'Could not launch $facebookUrl';
    }
  }

  static Future<void> makePhoneCall(String phoneNumber) async {
    final Uri phoneUri = Uri(
      scheme: 'tel',
      path: phoneNumber, // Phone number to call
    );

    try {
      await launchUrl(phoneUri);
    } catch (e) {
      print(e);
      throw CacheFailure();
    }
  }

  static Future<void> openWhatsAppChat(
      {required String num, String? message}) async {
    String phoneNumber = num.replaceAll(RegExp(r'\s+'), '');

    String whatsappUrl = "https://wa.me/$phoneNumber";
    if (message != null && message.isNotEmpty) {
      String encodedMessage = Uri.encodeComponent(message);
      whatsappUrl = "https://wa.me/$phoneNumber?text=$encodedMessage";
    }
    try {
      await launchUrl(Uri.parse(whatsappUrl));
    } catch (e) {
      throw ServerFailure(
          message:
              "the error is $e and Could not launch WhatsApp. URL: $whatsappUrl");
    }
  }

  // static Future<void> openWhatsAppChat() async {
  //   String phoneNumber =
  //       "+201008541308"; // Replace with the desired phone number
  //   String message =
  //       "Hello!"; // Optional: Replace with your desired initial message
  //
  //   String url = "https://wa.me/$phoneNumber/?text=${Uri.parse(message)}";
  //
  //   await launchUrl(Uri.parse(url));
  //   ; // Launch the WhatsApp URL
  // }

  static Future<void> openInstagram() async {
    const url = 'https://www.instagram.com'; // Instagram URL
    if (await canLaunch(url)) {
      await launch(url);
    } else {
      throw 'Could not launch $url';
    }
  }

  /// Make Stars
  static buildRatingStars(int rating) {
    String stars = '';
    for (int i = 0; i < rating; i++) {
      stars += '⭐ ';
    }
    stars.trim();
    return Text(stars);
  }
}
