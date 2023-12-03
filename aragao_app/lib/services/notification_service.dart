import 'package:flutter_local_notifications/flutter_local_notifications.dart';


class CustomNotification {
  int id;
  String title;
  String description;

  CustomNotification(this.id, this.title, this.description);
}

class NotificationService {
  late FlutterLocalNotificationsPlugin _localNotification;

  NotificationService() {
    _localNotification = FlutterLocalNotificationsPlugin();
    _localNotification
      .resolvePlatformSpecificImplementation<AndroidFlutterLocalNotificationsPlugin>()
      ?.requestNotificationsPermission();
    _setupNotifications();
  }

  _setupNotifications() async {
    const AndroidInitializationSettings androidSettings = AndroidInitializationSettings('@mipmap/launcher_icon');
    const DarwinInitializationSettings iosSettings = DarwinInitializationSettings();

    await _localNotification.initialize(
      const InitializationSettings(
        android: androidSettings,
        iOS: iosSettings
      ),
      onDidReceiveNotificationResponse: (details) {
        print('Notificação aberta!');
      },
    );
  }

  showNotification(CustomNotification notification) async {
    AndroidNotificationDetails androidDetails = AndroidNotificationDetails(
      'message_notification_${notification.id}',
      'Nova Mensagem',
      playSound: true,
      enableVibration: true,
      importance: Importance.max,
      priority: Priority.max
    );

    const DarwinNotificationDetails iosDetails = DarwinNotificationDetails(
      presentAlert: true,
      presentSound: true,
      presentBadge: true
    );

    await _localNotification.show(
      notification.id,
      notification.title,
      notification.description,
      NotificationDetails(
        android: androidDetails,
        iOS: iosDetails
      )
    );
  }
}