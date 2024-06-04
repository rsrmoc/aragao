import 'dart:convert';

class LatitudeLongitudeModel {
  int? requestTimeStamp;
  int userId;
  String lat;
  String long;
  LatitudeLongitudeModel({
    this.requestTimeStamp,
    required this.userId,
    required this.lat,
    required this.long,
  });

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'requestTimeStamp': requestTimeStamp,
      'latitude': lat,
      'longitude': long,
      'id_usuario': userId
    };
  }

  factory LatitudeLongitudeModel.fromMap(Map<String, dynamic> map) {
    return LatitudeLongitudeModel(
        requestTimeStamp: map['requestTimeStamp'] != null
            ? map['requestTimeStamp'] as int
            : null,
        lat: map['latitude'] as String,
        long: map['longitude'] as String,
        userId: map['id_usuario']);
  }

  String toJson() => json.encode(toMap());

  factory LatitudeLongitudeModel.fromJson(String source) =>
      LatitudeLongitudeModel.fromMap(
          json.decode(source) as Map<String, dynamic>);
}
