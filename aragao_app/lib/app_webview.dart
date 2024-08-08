import 'dart:async';
import 'dart:developer';
import 'dart:io';

import 'package:aragao_app/core/repo/app_repository.dart';
import 'package:aragao_app/model/latitude_longitude_model.dart';
import 'package:aragao_app/services/firebase_messaging_service.dart';
import 'package:aragao_app/services/notification_service.dart';
import 'package:background_fetch/background_fetch.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'package:webview_flutter/webview_flutter.dart';
import 'package:file_picker/file_picker.dart';
import 'package:image_picker/image_picker.dart';

// Import for Android features.
import 'package:webview_flutter_android/webview_flutter_android.dart';
// Import for iOS features.
import 'package:webview_flutter_wkwebview/webview_flutter_wkwebview.dart';

import 'package:url_launcher/url_launcher.dart';

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
  final ImagePicker _picker = ImagePicker();

  void fileSelectionHandler() async {
    if (Platform.isAndroid) {
      final androidController =
          (controller.platform as AndroidWebViewController);

      await androidController.setOnShowFileSelector(_androidFilePicker);
    }
  }

  Future<List<String>> _androidFilePicker(params) async {
    // Show a bottom sheet to select either camera or gallery
    return showModalBottomSheet<List<String>>(
      context: context,
      builder: (BuildContext context) {
        return SafeArea(
          child: Wrap(
            children: <Widget>[
              ListTile(
                leading: const Icon(Icons.camera_alt),
                title: const Text('Câmera'),
                onTap: () async {
                  final XFile? image = await _picker.pickImage(source: ImageSource.camera);
                  if (image != null) {
                    Navigator.pop(context, [image.path]);
                  } else {
                    Navigator.pop(context, []);
                  }
                },
              ),
              ListTile(
                leading: const Icon(Icons.photo_library),
                title: const Text('Galeria'),
                onTap: () async {
                  final XFile? image = await _picker.pickImage(source: ImageSource.gallery);
                  if (image != null) {
                    Navigator.pop(context, [image.path]);
                  } else {
                    Navigator.pop(context, []);
                  }
                },
              ),
            ],
          ),
        );
      },
    ).then((value) => value ?? []);
  }

  initializeFirebaseMessaging() async {
    await Provider.of<FirebaseMessagingService>(context, listen: false)
        .initializeSettings();

    Provider.of<NotificationService>(context, listen: false)
            .onSelectNotification =
        () => controller
            .loadRequest(Uri.parse('https://app.aragao.app.br/home/chat'));
  }

  void injectJavascriptSendTokenFirebaseMessaging() {
    String? token =
        Provider.of<FirebaseMessagingService>(context, listen: false).token;
    String? platform;

    if (Platform.isAndroid) platform = 'android';
    if (Platform.isIOS) platform = 'ios';

    if (token != null && platform != null) {
      controller.runJavaScript('''
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
      ''');
    }
  }

  @override
  void initState() {
    controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setBackgroundColor(const Color(0x00000000))
      ..setNavigationDelegate(NavigationDelegate(
        onPageStarted: (url) async {
          if (url.endsWith('/home') ||
              url.endsWith('/obras') ||
              url.endsWith('/chat')) {
            injectJavascriptSendTokenFirebaseMessaging();
          }
        },
        onPageFinished: (url) {
          if (url.endsWith('/home') ||
              url.endsWith('/obras') ||
              url.endsWith('/chat')) {
            injectJavascriptSendTokenFirebaseMessaging();
          }
        },
        onNavigationRequest: (NavigationRequest request) {
          final urlString = request.url.toUpperCase();
          final uriUL = Uri.parse(request.url);
          if (urlString.contains('STORAGE')) {
            launchUrl(uriUL);
            return NavigationDecision.prevent;
          }
          return NavigationDecision.navigate;
        },
      ))
      ..loadRequest(Uri.parse('https://app.aragao.app.br/'));

    fileSelectionHandler();

    initializeFirebaseMessaging();

    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: Container(
          color: Colors.black,
          child: SafeArea(child: WebViewWidget(controller: controller)),
        ));
  }
}
