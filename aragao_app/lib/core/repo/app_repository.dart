import 'dart:developer';

import 'package:aragao_app/core/network/custom_dio.dart';
import 'package:aragao_app/model/latitude_longitude_model.dart';

class AppRepository extends CustomDio {
  Future<void> inputLatLongInfoWithUserId(
      {required LatitudeLongitudeModel latLongModel}) async {
    try {
      final response = await dio.post(
        '/rastreamento/gravar',
        data: latLongModel.toJson(),
      );
    } catch (e) {
      log('API error', error: e);
    }
  }
}
