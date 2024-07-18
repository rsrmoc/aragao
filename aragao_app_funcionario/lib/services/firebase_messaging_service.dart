import 'package:aragao_app/services/notification_service.dart';
import 'package:firebase_messaging/firebase_messaging.dart';

class FirebaseMessagingService {
  late NotificationService _notificationService;
  late FirebaseMessaging _messaging;
  String? token;

  FirebaseMessagingService(this._notificationService) {
    _messaging = FirebaseMessaging.instance;
  }

  Future<void> initializeSettings() async {
    await _messaging.requestPermission(
      alert: true,
      announcement: true,
      badge: true,
      sound: true
    );

    token = await _messaging.getToken();

    _onMessage();
  }

  _onMessage() {
    FirebaseMessaging.onMessage.listen((message) {
      RemoteNotification? notification = message.notification;
      AndroidNotification? android = notification?.android;
      AppleNotification? ios = notification?.apple;

      if (notification != null && android != null) {        
        _notificationService.showNotification(CustomNotification(
          android.hashCode,
          notification!.title!,
          notification!.body!
        ));
      }

      if (notification != null && ios != null) {        
        _notificationService.showNotification(CustomNotification(
          ios.hashCode,
          notification!.title!,
          notification!.body!
        ));
      }
    });
  }
}