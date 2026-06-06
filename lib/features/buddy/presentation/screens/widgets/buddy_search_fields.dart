import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

import '../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../generated/l10n.dart';

class BuddySearchFields extends StatefulWidget {
  final ValueChanged<String> onUniversityChanged;
  final ValueChanged<String> onSubjectChanged;

  const BuddySearchFields({
    super.key,
    required this.onUniversityChanged,
    required this.onSubjectChanged,
  });

  @override
  State<BuddySearchFields> createState() => _BuddySearchFieldsState();
}

class _BuddySearchFieldsState extends State<BuddySearchFields> {
  final _universityController = TextEditingController();
  final _subjectController = TextEditingController();

  bool get _universityFilled => _universityController.text.isNotEmpty;
  bool get _subjectFilled => _subjectController.text.isNotEmpty;

  @override
  void dispose() {
    _universityController.dispose();
    _subjectController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Column(
        children: [
          _SearchField(
            controller: _universityController,
            hint: S.current.universityHint,
            icon: Icons.school_outlined,
            isFilled: _universityFilled,
            onChanged: (v) {
              setState(() {});
              widget.onUniversityChanged(v);
            },
          ),
          const Sizer(height: 10),
          _SearchField(
            controller: _subjectController,
            hint: S.current.subjectHint,
            icon: Icons.menu_book_outlined,
            isFilled: _subjectFilled,
            onChanged: (v) {
              setState(() {});
              widget.onSubjectChanged(v);
            },
          ),
        ],
      ),
    );
  }
}

class _SearchField extends StatelessWidget {
  final TextEditingController controller;
  final String hint;
  final IconData icon;
  final bool isFilled;
  final ValueChanged<String> onChanged;

  const _SearchField({
    required this.controller,
    required this.hint,
    required this.icon,
    required this.isFilled,
    required this.onChanged,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final iconColor = isFilled ? ColorRes.anisGreen : ColorRes.anisHintText;

    return Container(
      decoration: BoxDecoration(
        color: ColorRes.anisChipBg,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        border: isFilled
            ? Border.all(
                color: ColorRes.anisGreen.withValues(alpha: 0.4), width: 1.5)
            : null,
      ),
      child: TextField(
        controller: controller,
        onChanged: onChanged,
        textAlign: TextAlign.start,
        style: tt.bodyMedium?.copyWith(color: ColorRes.anisTextDark),
        decoration: InputDecoration(
          hintText: hint,
          hintStyle: tt.bodyMedium?.copyWith(color: ColorRes.anisHintText),
          // suffixIcon appears on the LEFT in RTL — the reading-start side
          suffixIcon: Padding(
            padding: EdgeInsets.only(right: AppSizes.sm),
            child: Icon(icon, size: 20.r, color: iconColor),
          ),
          suffixIconConstraints: const BoxConstraints(),
          border: InputBorder.none,
          contentPadding: EdgeInsets.symmetric(
            horizontal: AppSizes.md,
            vertical: AppSizes.sm + 4,
          ),
        ),
      ),
    );
  }
}
