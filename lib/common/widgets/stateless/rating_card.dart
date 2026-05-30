import 'package:flutter/material.dart';
import 'package:flutter_rating_bar/flutter_rating_bar.dart';
import '../../../core/constants/colors.dart';
import '../../../generated/l10n.dart';

class RatingCard extends StatelessWidget {
  final List<String> tags;
  final double rating;
  final Function(double) onRatingChanged;
  final Function(String) onTagSelected;
  final TextEditingController messageController;

  const RatingCard({
    super.key,
    required this.tags,
    required this.rating,
    required this.onRatingChanged,
    required this.onTagSelected,
    required this.messageController,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration:  BoxDecoration(
        color: Theme.of(context).scaffoldBackgroundColor,
        borderRadius:const BorderRadius.only(
          topLeft: Radius.circular(20),
          topRight: Radius.circular(20),
        ),
        boxShadow:const [
          BoxShadow(
            color: Colors.black26,
            blurRadius: 10,
          )
        ],
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Row(children: [
            const  SizedBox(width: 25,),
            const  Spacer(),
            Text(
              S.current.feed,
              style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const Spacer(),
            Container(
              height: 25,
              width: 25,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: Colors.grey.shade200,
              ),
              child: const Icon(Icons.close,color: Colors.black,),
            ),
          ],),
          const SizedBox(height: 8,),
          Text(
            getRatingText(rating),
            style:const TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 8),
          RatingBar.builder(
            initialRating: rating,
            minRating: 1,
            direction: Axis.horizontal,
            allowHalfRating: true,
            itemCount: 5,
            itemSize: 26,
            itemPadding: const EdgeInsets.symmetric(horizontal: 4.0),
            itemBuilder: (context, _) => const Icon(
              Icons.star,
              color: Colors.amber,
            ),
            onRatingUpdate: onRatingChanged,
          ),
          const SizedBox(height: 12),
          Wrap(
            spacing: 8,
            children: tags.map((tag) =>  GestureDetector(
              onTap: () => onTagSelected(tag),
              child: Container(
                width: MediaQuery.sizeOf(context).width*.41,
                height: 40,
                margin: const EdgeInsets.all(4),
                alignment: Alignment.center,
                decoration: BoxDecoration(
                    color:ColorRes.grey4,
                    borderRadius: BorderRadius.circular(12)
                ),
                child:Text(tag),

              ),
            )).toList(),
          ),
          const SizedBox(height: 12),
          GestureDetector(
            onTap: () {
            },
            child: Container(
              width: double.infinity,
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
              decoration: BoxDecoration(
                color: Colors.grey[200],
                borderRadius: BorderRadius.circular(30),
              ),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    S.current.thankFeed,
                    style:const TextStyle(color: Colors.grey),
                  ),
                  const Icon(Icons.arrow_forward_ios, color: Colors.grey, size: 16),
                ],
              ),
            ),
          ),
          const SizedBox(height: 12),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: ColorRes.primary,
                foregroundColor: Colors.white,
                padding:const EdgeInsets.symmetric(vertical: 12),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10),
                ),
              ),
              onPressed: () {
              },
              child: Text(S.current.send),
            ),
          ),
        ],
      ),
    );
  }
}
String getRatingText(double rating) {
  if (rating >= 4.5) return S.current.excellent;
  if (rating >= 3.5) return S.current.veryGood;
  if (rating >= 2.5) return S.current.good;
  if (rating >= 1.0) return S.current.poor;
  return S.current.noRating;
}
