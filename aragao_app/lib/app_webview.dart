import 'dart:io';

import 'package:aragao_app/services/firebase_messaging_service.dart';
import 'package:aragao_app/services/notification_service.dart';
import 'package:flutter/material.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:provider/provider.dart';

import 'package:webview_flutter/webview_flutter.dart';
import 'package:file_picker/file_picker.dart';

// Import for Android features.
import 'package:webview_flutter_android/webview_flutter_android.dart';
// Import for iOS features.
import 'package:webview_flutter_wkwebview/webview_flutter_wkwebview.dart';

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Aragão Construtora',
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.deepPurple),
        useMaterial3: true,
      ),
      home: const MyHomePage(),
      debugShowCheckedModeBanner: false,
    );
  }
}

class MyHomePage extends StatefulWidget {
  const MyHomePage({super.key});

  @override
  State<MyHomePage> createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> {
  late WebViewController controller;
  bool isNotification = false;
  
  void fileSelectionHandler() async {
    if (Platform.isAndroid) {
      final androidController = (controller.platform as AndroidWebViewController);

      await androidController.setOnShowFileSelector(_androidFilePicker);
    }
  }

  Future<List<String>> _androidFilePicker(params) async {
    final result = await FilePicker.platform.pickFiles();

    if (result != null && result.files.single.path != null) {
      final file = File(result.files.single.path!);

      return [file.uri.toString()];
    }

    return [];
  }

  initializeFirebaseMessaging() async {
    await Provider.of<FirebaseMessagingService>(context, listen: false).initializeSettings();

    Provider.of<NotificationService>(context, listen: false).onSelectNotification = () => controller.loadRequest(Uri.parse('https://app.aragao.app.br/home/chat'));
  }

  void injectJavascriptSendTokenFirebaseMessaging() {
    String? token = Provider.of<FirebaseMessagingService>(context, listen: false).token;
    String? platform;

    if (Platform.isAndroid) platform = 'android';
    if (Platform.isIOS) platform = 'ios';

    if (token != null && platform != null) {
      controller.runJavaScript('''
        document.addEventListener('DOMContentLoaded', () => {
          fetch('/home/notification-token', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
              token: '$token',
              platform: '$platform',
            })
          });
        });
      ''');
    }
  }

  @override
  void initState() {
    controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setBackgroundColor(const Color(0x00000000))
      ..setNavigationDelegate(
        NavigationDelegate(
          onPageStarted: (url) {
            if (url.endsWith('/home') || url.endsWith('/obras')) {
              injectJavascriptSendTokenFirebaseMessaging();
            }
          },
        )
      )
      ..loadRequest(Uri.parse('https://app.aragao.app.br/'));

    fileSelectionHandler();

    super.initState();

    initializeFirebaseMessaging();
  }

  @override 
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        color: Colors.black,
        child: SafeArea(
          child: WebViewWidget(controller: controller)
        ),
      )
    );
  }
}
