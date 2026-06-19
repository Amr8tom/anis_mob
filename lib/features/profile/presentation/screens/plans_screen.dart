import 'package:anis/core/constants/app_sizes.dart';
import 'package:flutter/material.dart';

import '../../../../common/widgets/appbar/appbar.dart';
import '../../../../core/constants/colors.dart';
import '../../../../core/constants/contancts.dart';
import '../../../../core/error/failure.dart';
import '../../../../core/service_locator/service_locator.dart';
import '../../../../generated/l10n.dart';
import '../../domain/use_cases/activate_subscription_code_use_case.dart';
import '../../domain/entity/subscription_activation_entity.dart';
import 'widgets/plans/plans_data.dart';
import 'widgets/plans/plans_detail_card_section.dart';
import 'widgets/plans/plans_tabs_section.dart';

class PlansScreen extends StatefulWidget {
  final String currentPlan;

  const PlansScreen({super.key, required this.currentPlan});

  @override
  State<PlansScreen> createState() => _PlansScreenState();
}

class _PlansScreenState extends State<PlansScreen> {
  static const _phone = '01123341488';
  static const _whatsAppPhone = '201123341488';
  static const _facebookPage = String.fromEnvironment(
    'ANIS_FACEBOOK_URL',
    defaultValue: 'https://www.facebook.com/profile.php?id=61585345364264',
  );

  @override
  Widget build(BuildContext context) {
    final plans = buildPlans();
    final initialIndex = plans.indexWhere((p) => p.key == widget.currentPlan);
    final index = initialIndex == -1 ? 0 : initialIndex;

    return DefaultTabController(
      length: plans.length,
      initialIndex: index,
      child: SafeArea(
        child: Scaffold(
          backgroundColor: ColorRes.anisMintBg,
          appBar: DAppBar(
            showBackArrow: true,
            title: S.current.choosePlanTitle,
          ),
          body: Column(
            children: [
              _ActivationBanner(
                onActivate: _showActivationDialog,
              ),
              PlansTabsSection(plans: plans),
              Expanded(
                child: TabBarView(
                  children: plans
                      .map(
                        (plan) => PlansDetailCardSection(
                          plan: plan,
                          isCurrent: plan.key == widget.currentPlan,
                          onChoosePlan: plan.key == 'free'
                              ? null
                              : () => _showPurchaseOptions(plan.title),
                        ),
                      )
                      .toList(),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Future<void> _showActivationDialog() async {
    final activated = await showDialog<SubscriptionActivationEntity>(
      context: context,
      builder: (_) => const _ActivationDialog(),
    );

    if (activated != null && mounted) {
      final message = activated.scope == 'workspace'
          ? '${activated.planName ?? S.current.planActivatedSuccessfully} • ${activated.workspaceName ?? ''}'
          : S.current.planActivatedSuccessfully;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(message),
          backgroundColor: ColorRes.anisButtonGreen,
          behavior: SnackBarBehavior.floating,
        ),
      );
    }
  }

  void _showPurchaseOptions(String planName) {
    final message = S.current.purchasePlanMessage(planName);

    showModalBottomSheet<void>(
      context: context,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
          borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
      builder: (context) => SafeArea(
        child: Padding(
          padding: const EdgeInsets.fromLTRB(24, 20, 24, 32),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Text(S.current.purchasePlanManually,
                  style: Theme.of(context)
                      .textTheme
                      .titleLarge
                      ?.copyWith(fontWeight: FontWeight.w800)),
              const SizedBox(height: 8),
              Text(
                S.current.purchasePlanManualDescription,
                style:
                    const TextStyle(color: ColorRes.anisTextMuted, height: 1.5),
              ),
              const SizedBox(height: 20),
              _ContactButton(
                  icon: Icons.chat_rounded,
                  label: S.current.whatsappNumber(_phone),
                  onTap: () => Contacts.openWhatsAppChat(
                      num: _whatsAppPhone, message: message)),
              _ContactButton(
                  icon: Icons.call_rounded,
                  label: S.current.callUs,
                  onTap: () => Contacts.makePhoneCall(_phone)),
              _ContactButton(
                  icon: Icons.facebook_rounded,
                  label: S.current.facebookPage,
                  onTap: () =>
                      Contacts.launchFacebookProfile(faceLink: _facebookPage)),
              const SizedBox(height: 8),
              OutlinedButton(
                  onPressed: () {
                    Navigator.pop(context);
                    _showActivationDialog();
                  },
                  child: Text(S.current.haveActivationCode)),
            ],
          ),
        ),
      ),
    );
  }
}

/// Owns its own [TextEditingController] so disposal is tied to the widget's
/// lifecycle — the controller stays alive through the dialog's close animation,
/// avoiding "used after being disposed" crashes. Pops `true` on success.
class _ActivationDialog extends StatefulWidget {
  const _ActivationDialog();

  @override
  State<_ActivationDialog> createState() => _ActivationDialogState();
}

class _ActivationDialogState extends State<_ActivationDialog> {
  final _controller = TextEditingController();
  bool _submitting = false;

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    final code = _controller.text.trim().toUpperCase();
    if (code.isEmpty) return;
    setState(() => _submitting = true);
    try {
      final result =
          await serviceLocator<ActivateSubscriptionCodeUseCase>().call(code);
      final activation = result.fold<SubscriptionActivationEntity>(
        (failure) => throw failure,
        (value) => value,
      );
      if (!mounted) return;
      Navigator.pop(context, activation);
    } on Failure catch (failure) {
      if (!mounted) return;
      setState(() => _submitting = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(getFailureMessage(failure, context)),
          behavior: SnackBarBehavior.floating,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(18)),
        title: Text(S.current.activatePlanCode),
        content: TextField(
          controller: _controller,
          textCapitalization: TextCapitalization.characters,
          decoration: InputDecoration(
            hintText: S.current.enterActivationCodeHint,
            filled: true,
            fillColor: ColorRes.anisInputBg,
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
          ),
        ),
        actions: [
          TextButton(
            onPressed: _submitting ? null : () => Navigator.pop(context),
            child: Text(
              S.current.cancel,
              style: Theme.of(context).textTheme.titleSmall,
            ),
          ),
          ElevatedButton(
            onPressed: _submitting ? null : _submit,
            style: ElevatedButton.styleFrom(
                backgroundColor: ColorRes.anisButtonGreen,
                foregroundColor: Colors.white),
            child: _submitting
                ? const SizedBox(
                    width: 18,
                    height: 18,
                    child: CircularProgressIndicator(
                        strokeWidth: 2, color: Colors.white))
                : Text(S.current.activate),
          ),
        ],
      );
}

class _ActivationBanner extends StatelessWidget {
  final VoidCallback onActivate;

  const _ActivationBanner({required this.onActivate});

  @override
  Widget build(BuildContext context) => Container(
        margin: const EdgeInsets.fromLTRB(16, 14, 16, 8),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: ColorRes.anisLine),
        ),
        child: Row(
          children: [
            const CircleAvatar(
                backgroundColor: ColorRes.anisTagGreen,
                child: Icon(Icons.key_rounded, color: ColorRes.anisGreen)),
            const SizedBox(width: 12),
            Expanded(
                child: Text(S.current.activationBannerDesc,
                    style: const TextStyle(fontWeight: FontWeight.w700))),
            TextButton(
                onPressed: onActivate,
                child: Text(
                  S.current.activateCode,
                  style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                      fontSize: AppSizes.fontSizeSm,
                      fontWeight: FontWeight.bold),
                )),
          ],
        ),
      );
}

class _ContactButton extends StatelessWidget {
  final IconData icon;
  final String label;
  final VoidCallback onTap;

  const _ContactButton(
      {required this.icon, required this.label, required this.onTap});

  @override
  Widget build(BuildContext context) => Padding(
        padding: const EdgeInsets.only(bottom: 10),
        child: ElevatedButton.icon(
          onPressed: onTap,
          icon: Icon(icon),
          label: Text(label),
          style: ElevatedButton.styleFrom(
            backgroundColor: ColorRes.anisButtonGreen,
            foregroundColor: Colors.white,
            elevation: 0,
            padding: const EdgeInsets.symmetric(vertical: 14),
            shape:
                RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          ),
        ),
      );
}
