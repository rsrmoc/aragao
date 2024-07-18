import 'dart:async';
import 'dart:developer';

import 'package:aragao_app/core/repo/app_repository.dart';

import 'package:flutter/material.dart';
import 'package:geolocator/geolocator.dart';
import 'package:shared_preferences/shared_preferences.dart';

import '../model/latitude_longitude_model.dart';

class LocalizationServices with ChangeNotifier {
  LocalizationServices._();
  static LocalizationServices? _instance;

  static LocalizationServices get instance {
    _instance ??= LocalizationServices._();
    return _instance!;
  }

  late Position currentPosition;
  late SharedPreferences localShared;

  final AppRepository _repository = AppRepository();
  AppRepository get repository => _repository;

  Future<void> initializeMapWithPermissions() async {
    bool serviceEnabled;
    LocationPermission permission;

    localShared = await SharedPreferences.getInstance();

    serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if (!serviceEnabled) {
      Geolocator.openLocationSettings();
      return Future.error('Location services are disabled.');
    }

    permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
      if (permission == LocationPermission.denied) {
        Geolocator.requestPermission();
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

  Future<void> fetchUserId({required String url}) async {
    Uri uri = Uri.parse(url);

    await localShared.setInt(
        'user_id', int.parse(uri.queryParameters['userId']!));
  }

  Future<void> sendLatLongReceiveTimestamp() async {
    final localId = localShared.getInt('user_id');
    print('Chamou a função!=====================================================================');
    print(currentPosition.latitude.toString());
    print(currentPosition.longitude.toString());
    print(localId);
    await repository.inputLatLongInfoWithUserId(
        latLongModel: LatitudeLongitudeModel(
            userId: localId ?? 0,
            lat: currentPosition.latitude.toString(),
            long: currentPosition.longitude.toString()));
  }
}
