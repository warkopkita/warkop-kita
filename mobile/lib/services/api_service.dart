import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../core/constants.dart';

class ApiService {
  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }

  static Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }

  static Future<void> saveUserRole(String role) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('user_role', role);
  }

  static Future<String?> getUserRole() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('user_role');
  }

  static Future<void> clearAuth() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
    await prefs.remove('user_role');
  }

  // 1. Auth: Login
  static Future<Map<String, dynamic>> login(String login, String password) async {
    final res = await http.post(
      Uri.parse('${AppConstants.baseUrl}/login'),
      headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
      body: jsonEncode({'login': login, 'password': password}),
    );
    return jsonDecode(res.body);
  }

  // 2. Menu: Categories & Products
  static Future<List<dynamic>> getCategories() async {
    final res = await http.get(Uri.parse('${AppConstants.baseUrl}/categories'));
    final json = jsonDecode(res.body);
    return json['data'] ?? [];
  }

  static Future<List<dynamic>> getProducts({String? categoryId, String? search}) async {
    var url = '${AppConstants.baseUrl}/products?';
    if (categoryId != null) url += 'category_id=$categoryId&';
    if (search != null) url += 'search=$search';
    
    final res = await http.get(Uri.parse(url));
    final json = jsonDecode(res.body);
    return json['data'] ?? [];
  }

  // 3. Orders: Submit Order
  static Future<Map<String, dynamic>> createOrder(Map<String, dynamic> orderData) async {
    final token = await getToken();
    final headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };

    final res = await http.post(
      Uri.parse('${AppConstants.baseUrl}/orders'),
      headers: headers,
      body: jsonEncode(orderData),
    );
    return jsonDecode(res.body);
  }

  // 4. Employee: Clock In with GPS & Photo
  static Future<Map<String, dynamic>> clockIn({
    required double latitude,
    required double longitude,
    required File photoFile,
    String? notes,
  }) async {
    final token = await getToken();
    final request = http.MultipartRequest(
      'POST',
      Uri.parse('${AppConstants.baseUrl}/attendance/clock-in'),
    );

    request.headers.addAll({
      'Accept': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    });

    request.fields['latitude'] = latitude.toString();
    request.fields['longitude'] = longitude.toString();
    if (notes != null) request.fields['notes'] = notes;

    request.files.add(
      await http.MultipartFile.fromPath('photo', photoFile.path),
    );

    final streamedResponse = await request.send();
    final response = await http.Response.fromStream(streamedResponse);
    return jsonDecode(response.body);
  }

  // 5. Employee: Clock Out
  static Future<Map<String, dynamic>> clockOut({
    required double latitude,
    required double longitude,
    required File photoFile,
    String? notes,
  }) async {
    final token = await getToken();
    final request = http.MultipartRequest(
      'POST',
      Uri.parse('${AppConstants.baseUrl}/attendance/clock-out'),
    );

    request.headers.addAll({
      'Accept': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    });

    request.fields['latitude'] = latitude.toString();
    request.fields['longitude'] = longitude.toString();
    if (notes != null) request.fields['notes'] = notes;

    request.files.add(
      await http.MultipartFile.fromPath('photo', photoFile.path),
    );

    final streamedResponse = await request.send();
    final response = await http.Response.fromStream(streamedResponse);
    return jsonDecode(response.body);
  }

  // 6. Owner: Summary stats
  static Future<Map<String, dynamic>> getOwnerSummary() async {
    final token = await getToken();
    final res = await http.get(
      Uri.parse('${AppConstants.baseUrl}/owner/summary'),
      headers: {
        'Accept': 'application/json',
        if (token != null) 'Authorization': 'Bearer $token',
      },
    );
    return jsonDecode(res.body);
  }

  // 7. Loyalty: Balance & Vouchers
  static Future<Map<String, dynamic>> getLoyaltyBalance() async {
    final token = await getToken();
    final res = await http.get(
      Uri.parse('${AppConstants.baseUrl}/loyalty/balance'),
      headers: {
        'Accept': 'application/json',
        if (token != null) 'Authorization': 'Bearer $token',
      },
    );
    return jsonDecode(res.body);
  }

  // 8. Attendance: Today's status
  static Future<Map<String, dynamic>> getAttendanceToday() async {
    final token = await getToken();
    final res = await http.get(
      Uri.parse('${AppConstants.baseUrl}/attendance/today'),
      headers: {
        'Accept': 'application/json',
        if (token != null) 'Authorization': 'Bearer $token',
      },
    );
    return jsonDecode(res.body);
  }
}
