import 'package:dio/dio.dart';
import 'package:crypto/crypto.dart' as crypto;

class AuthInterceptor extends Interceptor {
  String generateHashedToken() {
    final currentTime = DateTime.now().millisecondsSinceEpoch;
    const interval = 60;
    final timeInterval = (currentTime / interval).floor();
    const apiToken =
        'ArAjkIWctM5IVToSovd9pmbEpqbACiLQ6saom6FF5ICITJh1dwaWMlO1hryHgBe2';
    final message = '$timeInterval$apiToken';
    final hash = crypto.sha256.convert(message.codeUnits);
    return hash.toString();
  }

  @override
  Future<void> onRequest(
    RequestOptions options,
    RequestInterceptorHandler handler,
  ) async {
    var tempAuthToken = generateHashedToken();
    if (options.headers['requiresToken'] == false) {
      options.headers.remove('requiresToken');
      handler.next(options);
    }

    options.headers['Token'] = tempAuthToken;

    handler.next(options);
  }
}
