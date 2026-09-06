import 'package:flutter/foundation.dart';

class AppConstants {
  // Dukungan URL online via --dart-define=API_URL=https://...
  static const String customApiUrl = String.fromEnvironment('API_URL', defaultValue: '');

  static String get baseUrl {
    if (customApiUrl.isNotEmpty) {
      return customApiUrl.endsWith('/api') ? customApiUrl : '$customApiUrl/api';
    }
    if (kIsWeb) {
      return 'http://127.0.0.1:8000/api';
    }
    return 'http://10.0.2.2:8000/api';
  }

  static String get storageBaseUrl {
    if (customApiUrl.isNotEmpty) {
      final base = customApiUrl.replaceAll(RegExp(r'/api/?$'), '');
      return '$base/storage/';
    }
    if (kIsWeb) {
      return 'http://127.0.0.1:8000/storage/';
    }
    return 'http://10.0.2.2:8000/storage/';
  }

  static const String appName = 'Warkop Kita';
  static const String appTagline = 'Nongkrong Santai, Kopi Nikmat, Wi-Fi Kencang';
}
