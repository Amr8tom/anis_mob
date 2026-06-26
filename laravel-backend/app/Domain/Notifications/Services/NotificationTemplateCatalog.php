<?php

declare(strict_types=1);

namespace App\Domain\Notifications\Services;

use App\Models\Workspace;

final class NotificationTemplateCatalog
{
    /**
     * @return array<string, array<int, array{key:string,label:string,title:string,body:string}>>
     */
    public function workspace(?Workspace $workspace = null): array
    {
        $name = $workspace?->name ?? 'Anis';

        return [
            'ar' => [
                ['key' => 'special_offer', 'label' => 'عرض خاص', 'title' => "عرض خاص من {$name}", 'body' => "عندنا عرض خاص لفترة محدودة داخل {$name}. افتح التطبيق وشوف التفاصيل."],
                ['key' => 'session_reminder', 'label' => 'تذكير جلسة', 'title' => 'تذكير بموعد الجلسة', 'body' => "جلساتك القادمة داخل {$name} في انتظارك. افتح التطبيق للتفاصيل."],
                ['key' => 'subscription_expiry', 'label' => 'انتهاء اشتراك', 'title' => 'اشتراكك قرب ينتهي', 'body' => "اشتراكك في {$name} قرب ينتهي. جدده بدري عشان تفضل مستفيد."],
                ['key' => 'low_hours', 'label' => 'رصيد ساعات قليل', 'title' => 'ساعات اشتراكك قاربت على الانتهاء', 'body' => "رصيد ساعاتك في {$name} بقى قليل. اشحن أو جدد اشتراكك قبل ما يخلص."],
                ['key' => 'schedule_changed', 'label' => 'تغيير موعد', 'title' => 'تم تغيير الموعد', 'body' => "تم تحديث موعد مهم داخل {$name}. افتح التطبيق لمراجعة التفاصيل."],
                ['key' => 'room_confirmed', 'label' => 'تأكيد حجز غرفة', 'title' => 'تم تأكيد حجز الغرفة', 'body' => "تم تأكيد حجز غرفتك في {$name}. افتح التطبيق لمراجعة بيانات الحجز."],
            ],
            'en' => [
                ['key' => 'special_offer', 'label' => 'Special offer', 'title' => "Special offer from {$name}", 'body' => "A limited-time offer is available at {$name}. Open the app to see the details."],
                ['key' => 'session_reminder', 'label' => 'Session reminder', 'title' => 'Session reminder', 'body' => "Your upcoming sessions at {$name} are waiting for you. Open the app for details."],
                ['key' => 'subscription_expiry', 'label' => 'Subscription expiry', 'title' => 'Your subscription is ending soon', 'body' => "Your subscription at {$name} is ending soon. Renew early to keep using it."],
                ['key' => 'low_hours', 'label' => 'Low hours balance', 'title' => 'Your remaining hours are low', 'body' => "Your remaining hours at {$name} are low. Recharge or renew before they run out."],
                ['key' => 'schedule_changed', 'label' => 'Schedule changed', 'title' => 'Schedule updated', 'body' => "An important schedule at {$name} was updated. Open the app to review the details."],
                ['key' => 'room_confirmed', 'label' => 'Room booking confirmed', 'title' => 'Room booking confirmed', 'body' => "Your room booking at {$name} has been confirmed. Open the app to review it."],
            ],
            'tr' => [
                ['key' => 'special_offer', 'label' => 'Özel teklif', 'title' => "{$name} özel teklifi", 'body' => "{$name} içinde sınırlı süreli bir teklif var. Detayları görmek için uygulamayı açın."],
                ['key' => 'session_reminder', 'label' => 'Oturum hatırlatması', 'title' => 'Oturum hatırlatması', 'body' => "{$name} içindeki yaklaşan oturumlarınız sizi bekliyor. Detaylar için uygulamayı açın."],
                ['key' => 'subscription_expiry', 'label' => 'Abonelik bitişi', 'title' => 'Aboneliğiniz yakında bitiyor', 'body' => "{$name} aboneliğiniz yakında bitiyor. Devam etmek için erken yenileyin."],
                ['key' => 'low_hours', 'label' => 'Düşük saat bakiyesi', 'title' => 'Kalan saatleriniz azaldı', 'body' => "{$name} içinde kalan saatleriniz azaldı. Bitmeden önce yenileyin."],
                ['key' => 'schedule_changed', 'label' => 'Program değişti', 'title' => 'Program güncellendi', 'body' => "{$name} içinde önemli bir program güncellendi. Detayları görmek için uygulamayı açın."],
                ['key' => 'room_confirmed', 'label' => 'Oda rezervasyonu onaylandı', 'title' => 'Oda rezervasyonu onaylandı', 'body' => "{$name} oda rezervasyonunuz onaylandı. Detayları görmek için uygulamayı açın."],
            ],
        ];
    }
}
