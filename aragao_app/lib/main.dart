import 'dart:io';

import 'package:aragao_app/services/firebase_messaging_service.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'firebase_options.dart';

import 'package:aragao_app/services/notification_service.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'app_webview.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  await Firebase.initializeApp(
    options: DefaultFirebaseOptions.currentPlatform
  );

  runApp(
    MultiProvider(
      providers: [
        Provider<NotificationService>(create: (_) => NotificationService()),
        Provider<FirebaseMessagingService>(create: (context) => FirebaseMessagingService(context.read<NotificationService>()))
      ],
      child: const MyApp(),
    )
  );
}

