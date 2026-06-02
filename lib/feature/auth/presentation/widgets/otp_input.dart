import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../../../../core/constants/colors.dart';
import '../../../../core/constants/app_sizes.dart';

/// Custom OTP input create_request_support that matches the app's design system
class OtpInput extends StatefulWidget {
  const OtpInput({
    super.key,
    required this.length,
    required this.onCompleted,
    this.onChanged,
  });

  /// Number of OTP digits
  final int length;

  /// Callback when OTP is completed
  final Function(String) onCompleted;

  /// Callback when OTP value changes
  final Function(String)? onChanged;

  @override
  State<OtpInput> createState() => _OtpInputState();
}

class _OtpInputState extends State<OtpInput> {
  late List<TextEditingController> _controllers;
  late List<FocusNode> _focusNodes;
  String _currentOtp = '';

  @override
  void initState() {
    super.initState();
    _controllers = List.generate(
      widget.length,
      (index) => TextEditingController(),
    );
    _focusNodes = List.generate(
      widget.length,
      (index) => FocusNode(),
    );
  }

  @override
  void dispose() {
    for (var controller in _controllers) {
      controller.dispose();
    }
    for (var focusNode in _focusNodes) {
      focusNode.dispose();
    }
    super.dispose();
  }

  void _onChanged(String value, int index) {
    if (value.length == 1) {
      // Move to next field
      if (index < widget.length - 1) {
        _focusNodes[index + 1].requestFocus();
      } else {
        _focusNodes[index].unfocus();
      }
    } else if (value.isEmpty && index > 0) {
      // Move to previous field when deleting
      _focusNodes[index - 1].requestFocus();
    }

    // Update current OTP
    _currentOtp = _controllers.map((controller) => controller.text).join();
    
    // Call onChanged callback
    widget.onChanged?.call(_currentOtp);

    // Check if OTP is complete
    if (_currentOtp.length == widget.length) {
      widget.onCompleted(_currentOtp);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceEvenly,
      children: List.generate(
        widget.length,
        (index) => Container(
          width: AppSizes.buttonHeight,
          height: AppSizes.buttonHeight,
          decoration: BoxDecoration(
            border: Border.all(
              color: _controllers[index].text.isNotEmpty 
                  ? ColorRes.primary 
                  : ColorRes.grey.withOpacity(0.3),
              width: 1.5,
            ),
            borderRadius: BorderRadius.circular(AppSizes.sm),
            color: ColorRes.white,
          ),
          child: TextFormField(
            controller: _controllers[index],
            focusNode: _focusNodes[index],
            textAlign: TextAlign.center,
            style: Theme.of(context).textTheme.bodyLarge?.copyWith(
              fontWeight: FontWeight.w600,
              color: ColorRes.textPrimary,
            ),
            keyboardType: TextInputType.number,
            inputFormatters: [
              LengthLimitingTextInputFormatter(1),
              FilteringTextInputFormatter.digitsOnly,
            ],
            decoration: const InputDecoration(
              border: InputBorder.none,
              contentPadding: EdgeInsets.zero,
              counterText: '',
            ),
            onChanged: (value) => _onChanged(value, index),
          ),
        ),
      ),
    );
  }
}
