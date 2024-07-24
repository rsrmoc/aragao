import 'dart:developer';

import 'package:aragao_app/core/network/custom_dio.dart';
import 'package:aragao_app/model/latitude_longitude_model.dart';

class AppRepository extends CustomDio {
  Future<void> inputLatLongInfoWithUserId(
      {required LatitudeLongitudeModel latLongModel}) async {
    try {
      print("=============================Caiu no app repo!");
      print("============================= aqui e o lt: ! ${latLongModel.toJson()}");
      final response = await dio.post(
        '/rastreamento/gravar',
        data: latLongModel.toJson(),
      );
      print(response);
    } catch (e, stackTrace) {
      print("Caiu no erro! ========================== $e");
      log('API error', error: e, stackTrace: stackTrace);
    }
  }
}
