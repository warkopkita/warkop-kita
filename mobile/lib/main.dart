import 'package:flutter/material.dart';
import 'core/theme.dart';
import 'screens/login_screen.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  runApp(const WarkopDadangApp());
}

class WarkopDadangApp extends StatelessWidget {
  const WarkopDadangApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Warkop Kita Ecosystem',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.darkTheme,
      home: const LoginScreen(),
    );
  }
}
