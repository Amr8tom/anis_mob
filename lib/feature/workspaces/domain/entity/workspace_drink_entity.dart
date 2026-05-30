import 'package:equatable/equatable.dart';

class WorkspaceDrinkEntity extends Equatable {
  final String id;
  final String name;
  final String icon;
  final double price;

  const WorkspaceDrinkEntity({
    required this.id,
    required this.name,
    required this.icon,
    required this.price,
  });

  @override
  List<Object?> get props => [id, name, icon, price];
}

