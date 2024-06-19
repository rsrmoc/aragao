import 'dart:io';

import 'package:aragao_app/core/network/custom_dio.dart';
import 'package:aragao_app/services/firebase_messaging_service.dart';
import 'package:aragao_app/services/localization_services.dart';
import 'package:background_fetch/background_fetch.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'firebase_options.dart';

import 'package:aragao_app/services/notification_service.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'app_webview.dart';

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
