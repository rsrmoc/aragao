import 'dart:developer';

import 'package:aragao_app/core/network/custom_dio.dart';
import 'package:aragao_app/core/repo/app_repository.dart';

import 'package:flutter/material.dart';
import 'package:geolocator/geolocator.dart';

class LocalizationServices with ChangeNotifier {
  LocalizationServices._();
  static LocalizationServices? _instance;

  static LocalizationServices get instance {
    _instance ??= LocalizationServices._();
    return _instance!;
  }

  late Position currentPosition;
  int _requestTimeStamp = 10;
  int get requestTimeStamp => _requestTimeStamp;
  final AppRepository _repository = AppRepository();
  AppRepository get repository => _repository;

  Future<void> initializeMapWithPermissions() async {
    bool serviceEnabled;
    LocationPermission permission;

    serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if (!serviceEnabled) {
      return Future.error('Location services are disabled.');
    }

    permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
      if (permission == LocationPermission.denied) {
        return Future.error('Location permissions are denied');
      }
    }

    if (permission == LocationPermission.deniedForever) {
      return Future.error(
        'Location permissions are permanently denied, we cannot request permissions.',
      );
    }

    currentPosition = await Geolocator.getCurrentPosition(
      desiredAccuracy: LocationAccuracy.high,
    );

    log(currentPosition.toString());
  }

  Future<void> sendLatLongReceiveTimestamp(
      {required String lat, required String long}) async {
    repository.getResponseBackend();
  }

  void setNewTimestamp({required int newTimestamp}) {
    _requestTimeStamp = newTimestamp;
  }
}
