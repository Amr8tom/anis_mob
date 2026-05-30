part of 'navigation_cubit.dart';

@immutable
final class NavigationState extends Equatable {
  final NavigationStatus status;
  final bool isGuest;
  final List<Widget> screens;

  const NavigationState({
    this.isGuest = false,
    this.status = NavigationStatus.initialized,
    this.screens = const [
      HomeScreen(),
      BuddyScreen(),
      WorkspacesScreen(),
      ProfileScreen(),
    ],
  });

  NavigationState copyWith({
    NavigationStatus? status,
    bool? isGuest,
    List<Widget>? screens,
  }) {
    return NavigationState(
      status: status ?? this.status,
      isGuest: isGuest ?? this.isGuest,
      screens: screens ?? this.screens,
    );
  }

  @override
  List<Object?> get props => [status, isGuest, screens];
}
