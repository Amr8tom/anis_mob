<?php

declare(strict_types=1);

namespace App\Domain\Notifications\Services;

use App\Models\ScheduledNotificationTask;
use App\Models\StudySession;
use App\Models\Workspace;
use App\Models\WorkspacePrivateSession;
use App\Models\WorkspaceSubscription;

final class AutomaticNotificationTemplateRenderer
{
    /**
     * @return array{locale:string,title:string,body:string}
     */
    public function render(string $type, Workspace $workspace, StudySession|WorkspacePrivateSession|WorkspaceSubscription $entity): array
    {
        $locale = $this->localeFor($workspace);

        return match ($type) {
            ScheduledNotificationTask::TYPE_PUBLIC_SESSION_18H => $this->sessionReminder($locale, $workspace, $entity),
            ScheduledNotificationTask::TYPE_PRIVATE_SESSION_18H => $this->sessionReminder($locale, $workspace, $entity),
            ScheduledNotificationTask::TYPE_WORKSPACE_SUBSCRIPTION_EXPIRY_2D => $this->subscriptionExpiry($locale, $workspace),
            ScheduledNotificationTask::TYPE_WORKSPACE_SUBSCRIPTION_LOW_HOURS_16H => $this->subscriptionLowHours($locale, $workspace, $entity),
            default => [
                'locale' => $locale,
                'title' => 'Anis',
                'body' => 'لديك تحديث جديد.',
            ],
        };
    }

    private function localeFor(Workspace $workspace): string
    {
        $locale = (string) ($workspace->notification_locale ?? 'ar');

        return in_array($locale, ['ar', 'en', 'tr'], true) ? $locale : 'ar';
    }

    /**
     * @return array{locale:string,title:string,body:string}
     */
    private function sessionReminder(string $locale, Workspace $workspace, StudySession|WorkspacePrivateSession|WorkspaceSubscription $entity): array
    {
        $title = $entity instanceof WorkspaceSubscription ? '' : $entity->title;
        $workspaceName = $workspace->name;

        return match ($locale) {
            'en' => [
                'locale' => 'en',
                'title' => 'Session reminder',
                'body' => "Your session \"{$title}\" starts in 18 hours at {$workspaceName}.",
            ],
            'tr' => [
                'locale' => 'tr',
                'title' => 'Oturum hatırlatması',
                'body' => "\"{$title}\" oturumunuz {$workspaceName} konumunda 18 saat içinde başlıyor.",
            ],
            default => [
                'locale' => 'ar',
                'title' => 'تذكير بالجلسة',
                'body' => "جلسة {$title} تبدأ خلال 18 ساعة في {$workspaceName}.",
            ],
        };
    }

    /**
     * @return array{locale:string,title:string,body:string}
     */
    private function subscriptionExpiry(string $locale, Workspace $workspace): array
    {
        $workspaceName = $workspace->name;

        return match ($locale) {
            'en' => [
                'locale' => 'en',
                'title' => 'Subscription expiry reminder',
                'body' => "Your subscription at {$workspaceName} expires in 2 days.",
            ],
            'tr' => [
                'locale' => 'tr',
                'title' => 'Abonelik bitiş hatırlatması',
                'body' => "{$workspaceName} aboneliğiniz 2 gün içinde sona eriyor.",
            ],
            default => [
                'locale' => 'ar',
                'title' => 'تذكير بانتهاء الاشتراك',
                'body' => "اشتراكك في {$workspaceName} سينتهي خلال يومين.",
            ],
        };
    }

    /**
     * @return array{locale:string,title:string,body:string}
     */
    private function subscriptionLowHours(string $locale, Workspace $workspace, StudySession|WorkspacePrivateSession|WorkspaceSubscription $entity): array
    {
        $workspaceName = $workspace->name;
        $remainingHours = $entity instanceof WorkspaceSubscription
            ? max(1, (int) ceil($entity->remaining_minutes / 60))
            : 16;

        return match ($locale) {
            'en' => [
                'locale' => 'en',
                'title' => 'Low subscription balance',
                'body' => "You have less than 16 hours left in your {$workspaceName} subscription.",
            ],
            'tr' => [
                'locale' => 'tr',
                'title' => 'Abonelik süresi azalıyor',
                'body' => "{$workspaceName} aboneliğinizde 16 saatten az kaldı.",
            ],
            default => [
                'locale' => 'ar',
                'title' => 'ساعات الاشتراك قاربت على الانتهاء',
                'body' => "متبقي أقل من 16 ساعة في اشتراكك داخل {$workspaceName}. المتاح تقريبًا: {$remainingHours} ساعة.",
            ],
        };
    }
}
