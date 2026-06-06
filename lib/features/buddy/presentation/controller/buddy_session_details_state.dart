part of 'buddy_session_details_cubit.dart';

enum BuddySessionDetailsStatus { initial, loading, success, failure }

final class BuddySessionDetailsState extends Equatable {
  final BuddySessionDetailsStatus status;
  final BuddySessionEntity? session;
  final String? errorMessage;

  const BuddySessionDetailsState({
    this.status = BuddySessionDetailsStatus.initial,
    this.session,
    this.errorMessage,
  });

  BuddySessionDetailsState copyWith({
    BuddySessionDetailsStatus? status,
    BuddySessionEntity? session,
    String? errorMessage,
    bool clearErrorMessage = false,
  }) {
    return BuddySessionDetailsState(
      status: status ?? this.status,
      session: session ?? this.session,
      errorMessage:
          clearErrorMessage ? null : (errorMessage ?? this.errorMessage),
    );
  }

  @override
  List<Object?> get props => [status, session, errorMessage];
}
