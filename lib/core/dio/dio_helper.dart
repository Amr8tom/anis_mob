import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import 'package:pretty_dio_logger/pretty_dio_logger.dart';

import '../error/failure.dart';
import '../local_storage/local_storage.dart';
import '../local_storage/storage_keys.dart';

class DioHelper {
  final LocalStorage storage;
  final Dio dio;

  DioHelper(this.storage) : dio = Dio() {
    /// Adding Pretty Dio Logger for debugging
    dio.interceptors.add(
      PrettyDioLogger(
        requestHeader: true,
        requestBody: true,
        responseBody: true,
        responseHeader: false,
        error: true,
        compact: true,
        maxWidth: 90,
        enabled: kDebugMode,
      ),
    );
  }

  String get currentLanguage {
    final language = storage.getString(key: StorageKeys.lang.name);
    return language == null || language.isEmpty ? 'en' : language;
  }

  String get token => storage.getString(key: StorageKeys.token.name) ?? '';

  Map<String, String> get authHeaders => {
        "Authorization": "Bearer $token",
        "App-Language": currentLanguage,
      };

  Future getData({
    required String url,
    bool isHeader = true,
    Map<String, dynamic>? data,
  }) async {
    try {
      Response response = await dio.get(
        url,
        options: isHeader
            ? Options(
                headers: authHeaders,
              )
            : null,
        data: data,
      );
      if (response.statusCode == 200) {
        return response.data;
      }
    } on DioException {
      throw ServerFailure(
        message: '================== server failure =============',
      );
    }
  }

  Future<Map<String, dynamic>?> postData({
    // bool handleError = true,
    required String url,
    Map<String, dynamic>? body,
    String? token,
  }) async {
    try {
      Response response = await dio.post(
        url,
        data: body,
        options: Options(
          followRedirects: false,
          validateStatus: (status) => true,
          headers: authHeaders,
        ),
      );
      if (response.statusCode == 204 ||
          response.statusCode == 200 ||
          response.statusCode == 201) {
        return response.data;
      } else if (response.statusCode == 403 ||
          response.statusCode == 401 ||
          response.statusCode == 400) {
        if (response is String) {
          throw ServerFailure.fromString(response.data);
        } else {
          throw ServerFailure.fromMap(response.data);
        }
      } else if (response.statusCode == 400) {
        throw ServerFailure(message: "server failure");
      }
    } on DioException {
      rethrow;
    }
    return null;
  }

  Future<dynamic> postDataWithStringBody({
    // bool handleError = true,
    required String url,
    String? body,
    String? token,
  }) async {
    try {
      Response response = await dio.post(
        url,
        data: body,
        options: Options(
          followRedirects: false,
          validateStatus: (status) => true,
          headers: authHeaders,
        ),
      );
      if (response.statusCode == 204 ||
          response.statusCode == 200 ||

          ///401 unauthorized
          response.statusCode == 401 ||
          response.statusCode == 400 ||
          response.statusCode == 201) {
        return response.data;
      } else if (response.statusCode == 403) {
        throw ServerFailure(
          message: '================== server failure =============',
        );
      }
    } on DioException {
      rethrow;
    }
    return null;
  }

  Future<Map<String, dynamic>?> postFormData({
    bool handleError = true,
    required String url,
    FormData? formData,
    String? token,
  }) async {
    try {
      Response response = await dio.post(
        url,
        data: formData,
        options: Options(
          followRedirects: false,
          validateStatus: (status) => true,
          headers: {
            // 'Content-Type': 'application/json',
            'Content-Type': 'multipart/form-data',
            ...authHeaders,
          },
        ),
      );
      if (response.statusCode == 204 ||
          response.statusCode == 200 ||
          response.statusCode == 201) {
      } else if (response.statusCode == 403) {
        throw ServerFailure(
          message: '================== server failure =============',
        );
      }
      return response.data;
    } on DioException {
      rethrow;
    }
  }

  Future<Response> postDataWithoutAuth({
    bool handleError = true,
    required String url,
    Map<String, dynamic>? body,
    String? token,
  }) async {
    try {
      Response response = await dio.post(
        url,
        data: body,
        options: Options(
          /// validate status option to prevent dio from throwing error automatically and let me handle it
          followRedirects: false,
          validateStatus: (status) => true,
        ),
      );

      if (response.statusCode == 204 ||
          response.statusCode == 200 ||
          response.statusCode == 201) {
      } else if (response.statusCode == 403) {
        throw ServerFailure.fromString(response.data);
      } else if (response.statusCode == 401) {
        throw ServerFailure(message: " unauthorized");
      } else if (response.statusCode == 400) {
        throw ValidationFailure.fromMap(response.data);
      }
      return response;
    } on DioException {
      rethrow;
    }
  }

  Future<Response> putData({
    required String url,
    Map<String, dynamic>? body,
  }) async {
    return await dio.put(
      url,
      data: body,
      options: Options(
        headers: authHeaders,
      ),
    );
  }

  Future<Response> patchData({
    required String url,
    Map<String, dynamic>? body,
  }) async {
    return await dio.patch(
      url,
      data: body,
      options: Options(
        headers: authHeaders,
      ),
    );
  }

  Future<Response> deleteFromCart({
    required String url,
    Map<String, dynamic>? body,
  }) async {
    return await dio.put(
      url,
      data: body,
      options: Options(
        headers: authHeaders,
      ),
    );
  }

  Future<Response> deleteData({
    required String url,
    Map<String, dynamic>? body,
    // String? token,
  }) async {
    return await dio.delete(
      url,
      data: body,
      options: Options(
        headers: authHeaders,
      ),
    );
  }
}
