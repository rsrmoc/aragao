import 'package:dio/dio.dart';

abstract class BaseService {
  BaseService() {
    dio = Dio(BaseOptions(
      baseUrl: 'http://aragao.codetime.com.br/api',
      connectTimeout: const Duration(seconds: 5000),
      sendTimeout: const Duration(seconds: 3000),
      receiveTimeout: const Duration(seconds: 3000),
    ));
    dio.interceptors.addAll([
      //TODO: implement interceptor if have bearer token
    ]);
  }

  late Dio dio;
}
