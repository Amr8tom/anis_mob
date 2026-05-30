import 'package:flutter/material.dart';
import '../../../../common/widgets/appbar/appbar.dart';
import '../../../../generated/l10n.dart';
import '../widget/select_language_body.dart';

class SelectLanguageScreen extends StatelessWidget {
  const SelectLanguageScreen({super.key});

  @override
  Widget build(BuildContext context) {
    
    return Scaffold(
      appBar: DAppBar(

        title: S.current.selectLanguage,
      ),
      body: const SelectLanguageBody(),
    );
  }
}
