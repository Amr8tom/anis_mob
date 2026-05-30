import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

import '../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../generated/l10n.dart';
import '../../../domain/entity/workspace_entity.dart';

class WorkspaceCard extends StatelessWidget {
  final WorkspaceEntity workspace;
  final VoidCallback? onCheckIn;

  const WorkspaceCard({
    super.key,
    required this.workspace,
    this.onCheckIn,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Container(
      margin: EdgeInsets.symmetric(
        horizontal: AppSizes.padding,
        vertical: AppSizes.sm * 0.6,
      ),
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        boxShadow: [
          BoxShadow(
            color: ColorRes.anisNavy.withOpacity(0.07),
            blurRadius: 16,
            offset: const Offset(0, 4),
          ),
          BoxShadow(
            color: ColorRes.anisNavy.withOpacity(0.03),
            blurRadius: 4,
            offset: const Offset(0, 1),
          ),
        ],
      ),
      child: Padding(
        padding: EdgeInsets.all(AppSizes.spaceBetweenIcon + AppSizes.xs),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // ── Top row: name + status badge ─────────────────────
            Row(
              textDirection: TextDirection.rtl,
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.end,
                    children: [
                      Text(
                        workspace.name,
                        textAlign: TextAlign.right,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: tt.bodyMedium?.copyWith(
                          fontWeight: FontWeight.w700,
                          color: ColorRes.anisNavy,
                        ),
                      ),
                      const Sizer(height: 3),
                      Row(
                        textDirection: TextDirection.rtl,
                        children: [
                          Icon(
                            Icons.location_on_outlined,
                            size: 12.r,
                            color: ColorRes.anisHintText,
                          ),
                          const Sizer(width: 2),
                          Flexible(
                            child: Text(
                              workspace.address,
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                              style: tt.bodySmall?.copyWith(
                                color: ColorRes.anisHintText,
                              ),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
                const Sizer(width: 10),
                _StatusBadge(status: workspace.status),
              ],
            ),

            const Sizer(height: 14),

            // ── Occupancy bar ────────────────────────────────────
            Column(
              crossAxisAlignment: CrossAxisAlignment.end,
              children: [
                Row(
                  textDirection: TextDirection.rtl,
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      S.current.workspaceCapacity,
                      style: tt.bodySmall?.copyWith(
                        color: ColorRes.anisTextMuted,
                      ),
                    ),
                    Text(
                      '${workspace.currentOccupancy}/${workspace.capacity}',
                      style: tt.bodySmall?.copyWith(
                        fontWeight: FontWeight.w700,
                        color: _occupancyColor(workspace.occupancyFraction),
                      ),
                    ),
                  ],
                ),
                const Sizer(height: 6),
                ClipRRect(
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusSm),
                  child: LinearProgressIndicator(
                    value: workspace.occupancyFraction,
                    minHeight: 5.h,
                    backgroundColor: ColorRes.accent,
                    valueColor: AlwaysStoppedAnimation<Color>(
                      _occupancyColor(workspace.occupancyFraction),
                    ),
                  ),
                ),
              ],
            ),

            const Sizer(height: 12),
            Container(height: 1, color: ColorRes.accent.withOpacity(0.5)),
            const Sizer(height: 12),

            // ── Bottom row: hours + distance + check-in ──────────
            Row(
              textDirection: TextDirection.rtl,
              children: [
                _MetaItem(
                  icon: Icons.schedule_rounded,
                  label: '${workspace.openTime} - ${workspace.closeTime}',
                ),
                const Sizer(width: 12),
                _MetaItem(
                  icon: Icons.near_me_outlined,
                  label: '${workspace.distanceKm} ${S.current.km}',
                ),
                const Spacer(),
                if (workspace.isOpen)
                  _CheckInButton(onTap: onCheckIn)
                else
                  _ClosedLabel(status: workspace.status),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Color _occupancyColor(double fraction) {
    if (fraction < 0.6) return ColorRes.anisOnlineGreen;
    if (fraction < 0.9) return ColorRes.anisBusyAmber;
    return ColorRes.anisErrorRed;
  }
}

// ── Status badge ──────────────────────────────────────────────────────────────

class _StatusBadge extends StatelessWidget {
  final WorkspaceStatus status;
  const _StatusBadge({required this.status});

  @override
  Widget build(BuildContext context) {
    final (label, bg, fg) = switch (status) {
      WorkspaceStatus.open => (
          S.current.workspaceOpen,
          ColorRes.anisTagGreen,
          ColorRes.anisGreen,
        ),
      WorkspaceStatus.busy => (
          S.current.workspaceBusy,
          ColorRes.anisWarningBg,
          ColorRes.anisBusyAmber,
        ),
      WorkspaceStatus.full => (
          'ممتلئ',
          ColorRes.anisErrorRedBg,
          ColorRes.anisErrorRed,
        ),
      WorkspaceStatus.closed => (
          S.current.closedNow,
          ColorRes.accent,
          ColorRes.anisHintText,
        ),
    };

    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.sm,
        vertical: AppSizes.xs,
      ),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
      ),
      child: Text(
        label,
        style: Theme.of(context).textTheme.bodySmall?.copyWith(
              fontWeight: FontWeight.w700,
              color: fg,
              fontSize: 11,
            ),
      ),
    );
  }
}

// ── Meta item ─────────────────────────────────────────────────────────────────

class _MetaItem extends StatelessWidget {
  final IconData icon;
  final String label;
  const _MetaItem({required this.icon, required this.label});

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: 13.r, color: ColorRes.anisHintText),
        const Sizer(width: 3),
        Text(
          label,
          style: Theme.of(context).textTheme.bodySmall?.copyWith(
                color: ColorRes.anisTextMuted,
              ),
        ),
      ],
    );
  }
}

// ── Check-in button ───────────────────────────────────────────────────────────

class _CheckInButton extends StatelessWidget {
  final VoidCallback? onTap;
  const _CheckInButton({this.onTap});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: EdgeInsets.symmetric(
          horizontal: AppSizes.md,
          vertical: AppSizes.xs + 2,
        ),
        decoration: BoxDecoration(
          color: ColorRes.anisGreen,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        ),
        child: Text(
          S.current.scanQrShort,
          style: Theme.of(context).textTheme.bodySmall?.copyWith(
                fontWeight: FontWeight.w700,
                color: ColorRes.white,
              ),
        ),
      ),
    );
  }
}

// ── Closed label ──────────────────────────────────────────────────────────────

class _ClosedLabel extends StatelessWidget {
  final WorkspaceStatus status;
  const _ClosedLabel({required this.status});

  @override
  Widget build(BuildContext context) {
    final label = status == WorkspaceStatus.full ? 'ممتلئ' : S.current.closedNow;
    return Text(
      label,
      style: Theme.of(context).textTheme.bodySmall?.copyWith(
            color: ColorRes.anisHintText,
            fontWeight: FontWeight.w600,
          ),
    );
  }
}
