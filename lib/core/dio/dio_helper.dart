import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import 'package:anis/generated/l10n.dart';
import '../error/failure.dart';
import '../local_storage/cache_helper.dart';
import 'package:pretty_dio_logger/pretty_dio_logger.dart';
import '../local_storage/cache_keys.dart';

class DioHelper {
  /// Get current language from cache
  static String get currentLanguage =>
      CacheHelper.getString(key: CacheKeys.lang) ?? 'en';
  final Dio dio;
  DioHelper() : dio = Dio() {
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

  Future getData({
    required String URL,
    bool isHeader = true,
    Map<String, dynamic>? data,
  }) async {
    try {
      print(URL);
      print('Before response');
      Response response = await dio.get(
        URL,
        options:
            isHeader
                ? Options(
                  headers: {
                    "Authorization":
                        "Bearer ${CacheHelper.getString(key: CacheKeys.token)}",
                    "App-Language":
                        CacheHelper.getString(key: CacheKeys.lang) ?? 'en',
                  },
                )
                : null,
        data: data,
      );
      print('After response');
      if (response.statusCode == 200) {
        print(response.statusCode);
        print(response.data);
        return response.data;
      }
    } on DioException catch (error) {
      print("erro ========================================> $error");
      throw ServerFailure(
        message: '================== server failure =============',
      );
    }
  }

  Future<Map<String, dynamic>?> postData({
    // bool handleError = true,
    required String URL,
    Map<String, dynamic>? body,
    String? token,
  }) async {
    try {
      print(URL);
      print(body);
      Response response = await dio.post(
        URL,
        data: body,
        options: Options(
          followRedirects: false,
          validateStatus: (status) => true,
          headers: {
            "Authorization":
            "Bearer ${CacheHelper.getString(key: CacheKeys.token)}",
            "App-Language": CacheHelper.getString(key: CacheKeys.lang) ?? 'en',
          },
        ),
      );
      print(response.data);
      print(response.headers);
      if (response.statusCode == 204 ||
          response.statusCode == 200 ||
          response.statusCode == 201) {
        return response.data;
      } else if (response.statusCode == 403 || response.statusCode == 401|| response.statusCode == 400) {
        if(response is String){
          throw ServerFailure.fromString(response.data);
        }else {
          throw ServerFailure.fromMap(response.data);
        }
      }else if (response.statusCode == 400) {
        throw ServerFailure(message: "server failure");
      }
    } on DioException catch (error) {
      print("erro ========================================> $error");
      rethrow;
    }
    return null;
  }

  Future<dynamic> postDataWithStringBody({
    // bool handleError = true,
    required String URL,
    String? body,
    String? token,
  }) async {
    try {
      print(URL);
      print(body);
      Response response = await dio.post(
        URL,
        data: body,
        options: Options(
          followRedirects: false,
          validateStatus: (status) => true,
          headers: {
            "Authorization":
                "Bearer ${CacheHelper.getString(key: CacheKeys.token)}",
            "App-Language": CacheHelper.getString(key: CacheKeys.lang) ?? 'en',
          },
        ),
      );
      print(response.data);
      print(response.headers);
      if (response.statusCode == 204 ||
          response.statusCode == 200 ||
          ///401 unauthorized
          response.statusCode == 401 ||
          response.statusCode == 400 ||
          response.statusCode == 201) {
        print("//////////// API Data  Fetched Successfully  ////////////");
        print(response.data);
        return response.data;
      } else if (response.statusCode == 403) {
        throw ServerFailure(
          message: '================== server failure =============',
        );
      }
    } on DioException catch (error) {
      print("erro ========================================> $error");
      rethrow;
    }
    return null;
  }

  Future<Map<String, dynamic>?> postFormData({
    bool handleError = true,
    required String URL,
    FormData? formData,
    String? token,
  }) async {
    try {
      Response response = await dio.post(
        URL,
        data: formData,
        options: Options(
          followRedirects: false,
          validateStatus: (status) => true,
          headers: {
            // 'Content-Type': 'application/json',
            'Content-Type': 'multipart/form-data',
            "Authorization":
                "Bearer ${CacheHelper.getString(key: CacheKeys.token)}",
            "App-Language": CacheHelper.getString(key: CacheKeys.lang) ?? 'en',
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
    required String URL,
    Map<String, dynamic>? body,
    String? token,
  }) async {
    print(body);
    print(body);
    try {
      Response response = await dio.post(
        URL,
        data: body,
        options: Options(
          /// validate status option to prevent dio from throwing error automatically and let me handle it
          followRedirects: false,
          validateStatus: (status) => true,
        ),
      );
      print(response.statusCode);
      print(response.data);
      print(body);
      print(body);
      print(body);

      if (response.statusCode == 204 ||
          response.statusCode == 200 ||
          response.statusCode == 201) {
      } else if (response.statusCode == 403 ) {
        throw ServerFailure.fromString(response.data);
      }else if ( response.statusCode == 401){
        throw ServerFailure(message: " unauthorized");
      }
      else if (response.statusCode == 400) {
        throw ValidationFailure.fromMap(response.data);

      }
      return response;
    } on DioException {
      rethrow;
    }
  }

  Future<Response> putData({
    required String URL,
    Map<String, dynamic>? body,
  }) async {
    return await dio.put(
      URL,
      data: body,
      options: Options(
        headers: {
          "Authorization":
              "Bearer ${CacheHelper.getString(key: CacheKeys.token)}",
          "App-Language": currentLanguage,
        },
      ),
    );
  }

  Future<Response> patchData({
    required String URL,
    Map<String, dynamic>? body,
  }) async {
    return await dio.patch(
      URL,
      data: body,
      options: Options(
        headers: {
          "Authorization":
              "Bearer ${CacheHelper.getString(key: CacheKeys.token)}",
          "App-Language": currentLanguage,
        },
      ),
    );
  }

  Future<Response> deleteFromCart({
    required String URL,
    Map<String, dynamic>? body,
  }) async {
    return await dio.put(
      URL,
      data: body,
      options: Options(
        headers: {
          "Authorization":
              "Bearer ${CacheHelper.getString(key: CacheKeys.token)}",
          "App-Language": currentLanguage,
        },
      ),
    );
  }

  Future<Response> deleteData({
    required String URL,
    Map<String, dynamic>? body,
    // String? token,
  }) async {
    return await dio.delete(
      URL,
      data: body,
      options: Options(
        headers: {
          "Authorization":
              "Bearer ${CacheHelper.getString(key: CacheKeys.token)}",
          "App-Language": currentLanguage,
        },
      ),
    );
  }

  // static void logout(BuildContext context) async {
  //   await CacheHelper.clearShared();
  //   context.pushReplacementNamed(DRoutesName.loginRoute);
  // }
}
