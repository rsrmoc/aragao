import 'dart:developer';

import 'package:aragao_app/core/network/custom_dio.dart';

class AppRepository extends CustomDio {
  Future<void> getResponseBackend() async {
    try {
      final response = await dio.get('/facts');

      List rawContent = response.data;

      if (response.statusCode == 200) {
        for (var i = 0; i < 2; i++) {
          log(rawContent[0]['user']);
          log(rawContent[0]['text']);
        }
      }
    } catch (e) {
      log('API error', error: e);
    }
  }
}
