import 'dart:async';
import 'dart:io';

import 'package:aragao_app/core/network/custom_dio.dart';
import 'package:aragao_app/core/repo/app_repository.dart';
import 'package:aragao_app/model/latitude_longitude_model.dart';
import 'package:aragao_app/services/firebase_messaging_service.dart';
import 'package:aragao_app/services/localization_services.dart';
import 'package:background_fetch/background_fetch.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:workmanager/workmanager.dart';
import 'package:geolocator/geolocator.dart';
import 'firebase_options.dart';

import 'package:aragao_app/services/notification_service.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'app_webview.dart';

void callbackDispatcher() {
  Workmanager().executeTask((task, inputData) async {
    // Instanciar o repositório
    final AppRepository repository = AppRepository();

    // Obter a posição atual
    Position currentPosition = await Geolocator.getLastKnownPosition() ??
        await Geolocator.getCurrentPosition(
          desiredAccuracy: LocationAccuracy.high,
        );

    // Recuperar o ID do usuário do SharedPreferences
    SharedPreferences localShared = await SharedPreferences.getInstance();
    int? localId = localShared.getInt('user_id');

    print("Background task is running");
    print("Aqui é o ID do usuário logado: $localId - ${DateTime.now()}");
    print('Latitude: ${currentPosition.latitude}');
    print('Longitude: ${currentPosition.longitude}');

    await repository.inputLatLongInfoWithUserId(
      latLongModel: LatitudeLongitudeModel(
        userId: localId ?? 0,
        lat: currentPosition.latitude.toString(),
        long: currentPosition.longitude.toString(),
      ),
    );

    return Future.value(true);
  });
}

String generateUniqueId() {
  DateTime now = DateTime.now();
  String id =
      "${now.year}${now.month}${now.day}_${now.hour}${now.minute}${now.second}";
  return id;
}

@pragma('vm:entry-point')
void backgroundFetchHeadlessTask(HeadlessTask task) async {
  String taskId = task.taskId;
  bool isTimeout = task.timeout;
  if (isTimeout) {
    // This task has exceeded its allowed running-time.
    // You must stop what you're doing and immediately .finish(taskId)
    print("[BackgroundFetch] Headless task timed-out: $taskId");
    BackgroundFetch.finish(taskId);
    return;
  }
  print('[BackgroundFetch] Headless event received.');
  // Do your work here...
  BackgroundFetch.finish(taskId);
}

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  await Firebase.initializeApp(options: DefaultFirebaseOptions.currentPlatform);

  await SharedPreferences.getInstance();

  Workmanager().initialize(callbackDispatcher);

  Workmanager().registerPeriodicTask(
    generateUniqueId(),
    "locationSyncTask",
    frequency: Duration(seconds: 1), // Executa a cada 15 minutos
  );

  Geolocator.requestPermission();

  runApp(MultiProvider(
    providers: [
      Provider<NotificationService>(create: (_) => NotificationService()),
      Provider<FirebaseMessagingService>(
          create: (context) =>
              FirebaseMessagingService(context.read<NotificationService>())),
      Provider(create: (context) => CustomDio())
    ],
    child: const MyApp(),
  ));

  BackgroundFetch.registerHeadlessTask(backgroundFetchHeadlessTask);
}
