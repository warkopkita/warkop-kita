import 'package:flutter/material.dart';

class AppTheme {
  static const Color darkEspresso = Color(0xFF14110E);
  static const Color surfaceDark = Color(0xFF1E1914);
  static const Color cardDark = Color(0xFF26201A);
  static const Color borderDark = Color(0xFF3B3128);
  
  static const Color warmAmber = Color(0xFFD4A373);
  static const Color goldLatte = Color(0xFFE2B887);
  static const Color warmCream = Color(0xFFFAEDCD);
  
  static const Color textMain = Color(0xFFF4EAE0);
  static const Color textMuted = Color(0xFFA89F91);
  static const Color greenAccent = Color(0xFF10B981);
  static const Color redAccent = Color(0xFFEF4444);

  static ThemeData get darkTheme {
    return ThemeData(
      brightness: Brightness.dark,
      scaffoldBackgroundColor: darkEspresso,
      primaryColor: warmAmber,
      colorScheme: const ColorScheme.dark(
        primary: warmAmber,
        secondary: goldLatte,
        surface: surfaceDark,
      ),
      cardTheme: CardThemeData(
        color: cardDark,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
          side: const BorderSide(color: borderDark),
        ),
      ),
      appBarTheme: const AppBarTheme(
        backgroundColor: surfaceDark,
        elevation: 0,
        centerTitle: true,
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: warmAmber,
          foregroundColor: const Color(0xFF1A120B),
          textStyle: const TextStyle(fontWeight: FontWeight.w800, fontSize: 15),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
          padding: const EdgeInsets.symmetric(vertical: 14, horizontal: 20),
        ),
      ),
    );
  }
}
