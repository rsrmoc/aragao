import 'dart:convert';

class LatitudeLongitudeModel {
  int requestTimeStamp;
  String lat;
  String long;
  LatitudeLongitudeModel({
    required this.requestTimeStamp,
    required this.lat,
    required this.long,
  });

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'requestTimeStamp': requestTimeStamp,
      'lat': lat,
      'long': long,
    };
  }

  factory LatitudeLongitudeModel.fromMap(Map<String, dynamic> map) {
    return LatitudeLongitudeModel(
      requestTimeStamp: map['requestTimeStamp'] as int,
      lat: map['lat'] as String,
      long: map['long'] as String,
    );
  }

  String toJson() => json.encode(toMap());

  factory LatitudeLongitudeModel.fromJson(String source) =>
      LatitudeLongitudeModel.fromMap(
          json.decode(source) as Map<String, dynamic>);
}
