import 'package:flutter_test/flutter_test.dart';
import 'package:warkop_dadang_mobile/main.dart';

void main() {
  testWidgets('Warkop App smoke test', (WidgetTester tester) async {
    await tester.pumpWidget(const WarkopDadangApp());
    expect(find.text('WARKOP DADANG'), findsOneWidget);
  });
}
