import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart' as url_launcher;

import '../../../../../../common/custom_ui.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/workspace_drink_entity.dart';
import '../../../../domain/entity/workspace_entity.dart';
import 'workspace_gallery_image.dart';

class WorkspaceInfoTab extends StatelessWidget {
  final WorkspaceEntity workspace;

  const WorkspaceInfoTab({super.key, required this.workspace});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      physics: const BouncingScrollPhysics(),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // Gallery
          if (workspace.galleryImages.isNotEmpty) ...[
            _WorkspaceGallery(images: workspace.galleryImages),
            Sizer(height: AppSizes.md),
          ],

          // Description
          if (workspace.description.isNotEmpty) ...[
            _WorkspaceDescriptionSection(description: workspace.description),
            Sizer(height: AppSizes.md),
          ],

          _WorkspaceBillingSection(
            workspace: workspace,
          ),
          Sizer(height: AppSizes.md),

          // Amenities
          if (workspace.amenities.isNotEmpty) ...[
            _WorkspaceAmenitiesSection(amenities: workspace.amenities),
            Sizer(height: AppSizes.md),
          ],

          // Drinks
          _WorkspaceDrinksSection(drinks: workspace.drinks),
          Sizer(height: AppSizes.md),

          // Location
          _WorkspaceLocationCard(
            latitude: workspace.latitude,
            longitude: workspace.longitude,
            address: workspace.address,
          ),
          Sizer(height: AppSizes.xl),
        ],
      ),
    );
  }
}

// ── Gallery ──────────────────────────────────────────────────────────────────

class _WorkspaceGallery extends StatelessWidget {
  final List<String> images;
  const _WorkspaceGallery({required this.images});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 200.0,
      child: ListView.separated(
        scrollDirection: Axis.horizontal,
        padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
        itemCount: images.length,
        separatorBuilder: (_, __) => Sizer(width: AppSizes.sm),
        itemBuilder: (_, index) {
          return ClipRRect(
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
            child: Container(
              width: 280.0,
              color: ColorRes.anisChipBg,
              child: WorkspaceGalleryImage(source: images[index]),
            ),
          );
        },
      ),
    );
  }
}

// ── Description ───────────────────────────────────────────────────────────────

class _WorkspaceDescriptionSection extends StatelessWidget {
  final String description;
  const _WorkspaceDescriptionSection({required this.description});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Container(
        padding: EdgeInsets.all(AppSizes.md),
        decoration: BoxDecoration(
          color: ColorRes.white,
          border: Border(
            right: BorderSide(
              color: ColorRes.anisGreen,
              width: 4,
            ),
          ),
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          boxShadow: [
            BoxShadow(
              color: ColorRes.anisNavy.withValues(alpha: 0.04),
              blurRadius: 10,
              offset: const Offset(0, 3),
            ),
          ],
        ),
        child: Text(
          description,
          textAlign: TextAlign.start,
          style: tt.bodyMedium?.copyWith(
            color: ColorRes.anisTextSecondary,
            height: 1.6,
          ),
        ),
      ),
    );
  }
}

class _WorkspaceBillingSection extends StatelessWidget {
  final WorkspaceEntity workspace;
  const _WorkspaceBillingSection({required this.workspace});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final multiplier = workspace.hourMultiplier;
    final capHours =
        (workspace.dayCalculationHours * multiplier).toStringAsFixed(0);
    final realHours = workspace.dayCalculationHours.toString();

    String multiplierText;
    if (multiplier == 1.0) {
      multiplierText = S.current.workspaceHourMultiplierStandard;
    } else if (multiplier == 2.0) {
      multiplierText = S.current.workspaceHourMultiplierPremium;
    } else if (multiplier == 0.0) {
      multiplierText = S.current.workspaceHourMultiplierFree;
    } else {
      multiplierText = S.current
          .workspaceHourMultiplierCustom(multiplier.toStringAsFixed(1));
    }

    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Container(
        padding: EdgeInsets.all(AppSizes.md),
        decoration: BoxDecoration(
          color: ColorRes.anisGold.withValues(alpha: 0.12),
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          border: Border.all(
            color: ColorRes.anisGold.withValues(alpha: 0.35),
          ),
        ),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              width: 38,
              height: 38,
              decoration: BoxDecoration(
                color: ColorRes.anisGold.withValues(alpha: 0.18),
                shape: BoxShape.circle,
              ),
              child: const Icon(
                Icons.timer_outlined,
                color: ColorRes.anisGold,
                size: 20,
              ),
            ),
            Sizer(width: AppSizes.sm),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    S.current.workspaceDayCalculationTitle,
                    style: tt.titleSmall?.copyWith(
                      fontWeight: FontWeight.w700,
                      color: ColorRes.anisNavy,
                    ),
                  ),
                  Sizer(height: AppSizes.xs),
                  Text(
                    multiplierText,
                    style: tt.bodySmall?.copyWith(
                      fontWeight: FontWeight.w600,
                      color: ColorRes.anisNavy,
                      height: 1.5,
                    ),
                  ),
                  if (multiplier > 0.0) ...[
                    Sizer(height: AppSizes.xs),
                    Text(
                      S.current.workspaceDailyCapText(capHours, realHours),
                      style: tt.bodySmall?.copyWith(
                        color: ColorRes.anisTextMuted,
                        height: 1.5,
                      ),
                    ),
                  ],
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// Amenities

class _WorkspaceAmenitiesSection extends StatelessWidget {
  final List<String> amenities;
  const _WorkspaceAmenitiesSection({required this.amenities});

  static const _icons = <String, IconData>{
    'wifi': Icons.wifi_rounded,
    'ac': Icons.ac_unit_rounded,
    'coffee': Icons.local_cafe_rounded,
    'printing': Icons.print_rounded,
    'quiet': Icons.volume_off_rounded,
  };

  static const _labels = <String, String>{
    'wifi': 'واي فاي',
    'ac': 'تكييف',
    'coffee': 'قهوة',
    'printing': 'طباعة',
    'quiet': 'هادئ',
  };

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            S.current.amenities,
            textAlign: TextAlign.start,
            style: tt.titleSmall?.copyWith(
              fontWeight: FontWeight.w700,
              color: ColorRes.anisNavy,
            ),
          ),
          Sizer(height: AppSizes.sm),
          Wrap(
            spacing: AppSizes.sm,
            runSpacing: AppSizes.sm,
            alignment: WrapAlignment.start,
            children: amenities.map((key) {
              return Container(
                padding: EdgeInsets.symmetric(
                  horizontal: AppSizes.md,
                  vertical: AppSizes.xs + 2,
                ),
                decoration: BoxDecoration(
                  color: ColorRes.anisCardBg,
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
                  border: Border.all(color: ColorRes.anisLine),
                ),
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Icon(
                      _icons[key] ?? Icons.check_circle_outline,
                      size: AppSizes.iconSm,
                      color: ColorRes.anisGreen,
                    ),
                    Sizer(width: AppSizes.xs),
                    Text(
                      _labels[key] ?? key,
                      style: tt.bodySmall?.copyWith(
                        fontWeight: FontWeight.w600,
                        color: ColorRes.anisTextDark,
                      ),
                    ),
                  ],
                ),
              );
            }).toList(),
          ),
        ],
      ),
    );
  }
}

// ── Drinks ────────────────────────────────────────────────────────────────────

class _WorkspaceDrinksSection extends StatelessWidget {
  final List<WorkspaceDrinkEntity> drinks;
  const _WorkspaceDrinksSection({required this.drinks});

  static const _drinkEmoji = <String, String>{
    'Espresso': '☕',
    'Cappuccino': '☕',
    'Latte': '🥛',
    'Americano': '☕',
    'Mocha': '🍫',
    'Tea': '🍵',
    'Hot Chocolate': '🍫',
    'Cold Brew': '🧊',
    'Orange Juice': '🍊',
    'Iced Coffee': '🧋',
  };

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            S.current.drinksMenu,
            textAlign: TextAlign.start,
            style: tt.titleSmall?.copyWith(
              fontWeight: FontWeight.w700,
              color: ColorRes.anisNavy,
            ),
          ),
          Sizer(height: AppSizes.sm),
          if (drinks.isEmpty)
            CustomUI.anisEmptyState(
              context: context,
              icon: Icons.local_cafe_outlined,
              title: S.current.noData,
            )
          else
            ...drinks.map((drink) => _DrinkRow(
                drink: drink, emoji: _drinkEmoji[drink.name] ?? '🥤')),
        ],
      ),
    );
  }
}

class _DrinkRow extends StatelessWidget {
  final WorkspaceDrinkEntity drink;
  final String emoji;
  const _DrinkRow({required this.drink, required this.emoji});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Container(
      margin: EdgeInsets.only(bottom: AppSizes.sm),
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.md,
        vertical: AppSizes.sm + 2,
      ),
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        boxShadow: [
          BoxShadow(
            color: ColorRes.anisNavy.withValues(alpha: 0.04),
            blurRadius: 10,
            offset: const Offset(0, 3),
          ),
        ],
      ),
      child: Row(
        children: [
          // Emoji icon container
          Container(
            width: 44.0,
            height: 44.0,
            decoration: BoxDecoration(
              color: ColorRes.anisCardBg,
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
            ),
            alignment: Alignment.center,
            child: Text(emoji, style: const TextStyle(fontSize: 22)),
          ),
          Sizer(width: AppSizes.sm),
          // Name
          Expanded(
            child: Text(
              drink.name,
              textAlign: TextAlign.start,
              style: tt.bodyMedium?.copyWith(
                fontWeight: FontWeight.w600,
                color: ColorRes.anisNavy,
              ),
            ),
          ),
          Sizer(width: AppSizes.sm),
          // Price badge
          Container(
            padding: EdgeInsets.symmetric(
              horizontal: AppSizes.sm + 2,
              vertical: AppSizes.xs,
            ),
            decoration: BoxDecoration(
              color: ColorRes.anisGreen.withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
            ),
            child: Text(
              '${drink.price.toStringAsFixed(0)} EGP',
              style: tt.bodySmall?.copyWith(
                fontWeight: FontWeight.w700,
                color: ColorRes.anisGreen,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

// ── Location Card ─────────────────────────────────────────────────────────────

class _WorkspaceLocationCard extends StatelessWidget {
  final double latitude;
  final double longitude;
  final String address;

  const _WorkspaceLocationCard({
    required this.latitude,
    required this.longitude,
    required this.address,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Container(
        padding: EdgeInsets.all(AppSizes.md),
        decoration: BoxDecoration(
          color: ColorRes.white,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          boxShadow: [
            BoxShadow(
              color: ColorRes.anisNavy.withValues(alpha: 0.04),
              blurRadius: 10,
              offset: const Offset(0, 3),
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(
                  Icons.location_on_rounded,
                  color: ColorRes.anisGreen,
                  size: AppSizes.iconSm,
                ),
                Sizer(width: AppSizes.xs),
                Text(
                  S.current.location,
                  style: tt.titleSmall?.copyWith(
                    fontWeight: FontWeight.w700,
                    color: ColorRes.anisNavy,
                  ),
                ),
              ],
            ),
            Sizer(height: AppSizes.xs + 2),
            Text(
              address,
              textAlign: TextAlign.start,
              style: tt.bodySmall?.copyWith(
                color: ColorRes.anisTextMuted,
              ),
            ),
            Sizer(height: AppSizes.md),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton.icon(
                onPressed: () => _openMaps(context),
                style: ElevatedButton.styleFrom(
                  backgroundColor: ColorRes.anisGreen,
                  foregroundColor: ColorRes.white,
                  padding: EdgeInsets.symmetric(vertical: AppSizes.sm + 2),
                  shape: RoundedRectangleBorder(
                    borderRadius:
                        BorderRadius.circular(AppSizes.borderRadiusLg),
                  ),
                  elevation: 0,
                ),
                icon: const Icon(Icons.map_outlined, size: 18),
                label: Text(
                  S.current.openInMaps,
                  style: tt.bodyMedium?.copyWith(
                    fontWeight: FontWeight.w700,
                    color: ColorRes.white,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Future<void> _openMaps(BuildContext context) async {
    final url =
        'https://www.google.com/maps/search/?api=1&query=$latitude,$longitude';
    final uri = Uri.parse(url);
    if (await url_launcher.canLaunchUrl(uri)) {
      await url_launcher.launchUrl(
        uri,
        mode: url_launcher.LaunchMode.externalApplication,
      );
    }
  }
}
