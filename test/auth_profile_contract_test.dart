import 'package:anis/features/auth/data/models/login_model.dart';
import 'package:anis/features/auth/domain/use_cases/create_user_use_case.dart';
import 'package:anis/features/profile/domain/use_cases/update_profile_use_case.dart';
import 'package:flutter_test/flutter_test.dart';

void main() {
  group('auth and profile API contracts', () {
    test('signup sends the confirmed strong-password fields', () {
      const params = CreateUserInfoParams(
        fullName: 'Anis Student',
        phoneNumber: '01000000001',
        whatsAppNumber: '01000000002',
        password: 'Secret123',
        passwordConfirmation: 'Secret123',
      );

      expect(params.toMap(), {
        'full_name': 'Anis Student',
        'phone_number': '01000000001',
        'whatsapp_number': '01000000002',
        'password': 'Secret123',
        'password_confirmation': 'Secret123',
      });
    });

    test('login reads profile completion status from the user response', () {
      final model = LoginModel.fromJson({
        'success': true,
        'data': {
          'token': 'token',
          'user': {
            'full_name': 'Anis Student',
            'role': 'USER',
            'profile_completed': false,
          },
        },
      });

      expect(model.profileCompleted, isFalse);
    });

    test('profile update maps fields to the Laravel request contract', () {
      const params = UpdateProfileParams(
        email: ' student@anis.test ',
        university: ' Cairo University ',
        studyField: ' Engineering ',
        gender: 'male',
        interests: ['Programming'],
      );

      expect(params.toMap(), {
        'email': 'student@anis.test',
        'university': 'Cairo University',
        'study_field': 'Engineering',
        'gender': 'male',
        'interests': ['Programming'],
      });
    });
  });
}
