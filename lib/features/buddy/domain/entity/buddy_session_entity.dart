import 'package:equatable/equatable.dart';

import '../../../workspaces/domain/entity/workspace_entity.dart';
import 'buddy_member_entity.dart';

enum BuddyAvailability { online, busy, offline }

enum BuddySessionStatus { open, full, inProgress }

class BuddySessionEntity extends Equatable {
  final String id;

  // ── Founder info ─────────────────────────────────────────────────────────
  final String buddyName;
  final String buddyInitials;
  /// 'red' | 'blue' | 'purple' | 'green' | 'orange'
  final String avatarColorKey;
  final String university;
  final BuddyAvailability availability;

  // ── Session content ───────────────────────────────────────────────────────
  final String topic;
  final String subject;
  final String description;
  final List<String> rules;
  final String? gift;
  final List<BuddyMemberEntity> members;
  final int maxCapacity;

  // ── Schedule ──────────────────────────────────────────────────────────────
  final DateTime startTime;
  final String timeLabel;

  // ── Workspace ─────────────────────────────────────────────────────────────
  /// Full workspace entity — used to display info and navigate to details.
  final WorkspaceEntity workspace;

  // ── Status ────────────────────────────────────────────────────────────────
  final BuddySessionStatus sessionStatus;

  const BuddySessionEntity({
    required this.id,
    required this.buddyName,
    required this.buddyInitials,
    required this.avatarColorKey,
    required this.university,
    required this.availability,
    required this.topic,
    required this.subject,
    required this.description,
    this.rules = const [],
    this.gift,
    this.members = const [],
    this.maxCapacity = 6,
    required this.startTime,
    required this.timeLabel,
    required this.workspace,
    this.sessionStatus = BuddySessionStatus.open,
  });

  bool get isOnline => availability == BuddyAvailability.online;
  int get memberCount => members.length;
  bool get isFull => memberCount >= maxCapacity;

  @override
  List<Object?> get props => [
        id, buddyName, buddyInitials, avatarColorKey, university, availability,
        topic, subject, description, rules, gift, members, maxCapacity,
        startTime, timeLabel, workspace, sessionStatus,
      ];
}
