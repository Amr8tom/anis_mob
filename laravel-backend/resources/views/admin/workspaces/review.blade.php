@extends('admin.layout.app')

@section('title', 'مراجعة تسجيل: ' . $workspace->name . ' | بوابة الإدارة')
@section('header', 'مراجعة طلب تسجيل مساحة عمل')

@section('content')
    @if ($errors->any())
        <div class="card" style="border-right: 4px solid var(--upwork-error); margin-bottom: 20px;">
            @foreach ($errors->all() as $error)
                <div style="color: var(--upwork-error); font-weight: 600;">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card" style="margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
        <span style="padding: 6px 12px; background-color: var(--upwork-warning-bg, #fff7e6); color: #b76e00; border-radius: var(--radius-sm); font-weight: 700;">
            <i class="fa-solid fa-hourglass-half"></i> بانتظار المراجعة
        </span>
        <span style="color: var(--upwork-muted);">راجع البيانات والصور أدناه ثم اتخذ القرار.</span>
    </div>

    {{-- Images --}}
    <div class="card" style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 16px;">الصور</h3>

        @if ($workspace->cover_image_url)
            <div style="margin-bottom: 16px;">
                <div style="font-weight: 700; margin-bottom: 8px; font-size: 14px;">صورة الغلاف</div>
                <a href="{{ $workspace->cover_image_url }}" target="_blank" rel="noopener">
                    <img src="{{ $workspace->cover_image_url }}" alt="cover"
                         style="max-width: 100%; max-height: 320px; border-radius: var(--radius-sm); border: 1px solid var(--upwork-border);">
                </a>
            </div>
        @endif

        @php($gallery = $workspace->gallery_urls)
        @if (!empty($gallery))
            <div style="font-weight: 700; margin-bottom: 8px; font-size: 14px;">معرض الصور ({{ count($gallery) }})</div>
            <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                @foreach ($gallery as $img)
                    <a href="{{ $img }}" target="_blank" rel="noopener">
                        <img src="{{ $img }}" alt="gallery"
                             style="width: 160px; height: 120px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--upwork-border);">
                    </a>
                @endforeach
            </div>
        @endif

        @if (!$workspace->cover_image_url && empty($gallery))
            <div style="color: var(--upwork-muted);">لم يتم رفع أي صور.</div>
        @endif
    </div>

    {{-- Submitted data --}}
    <div class="card" style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 16px;">بيانات مساحة العمل</h3>
        <div class="grid-2" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
            @php
                $rows = [
                    'الاسم' => $workspace->name,
                    'العنوان' => $workspace->address,
                    'الوصف' => $workspace->description ?: '—',
                    'السعة' => $workspace->capacity ?: '—',
                    'وقت الفتح' => $workspace->open_time ?: '—',
                    'وقت الإغلاق' => $workspace->close_time ?: '—',
                    'ساعات احتساب اليوم' => $workspace->day_calculation_hours ?: '—',
                ];
            @endphp
            @foreach ($rows as $label => $value)
                <div>
                    <div style="font-size: 13px; color: var(--upwork-muted); margin-bottom: 4px;">{{ $label }}</div>
                    <div style="font-weight: 600;">{{ $value }}</div>
                </div>
            @endforeach
            <div>
                <div style="font-size: 13px; color: var(--upwork-muted); margin-bottom: 4px;">الموقع</div>
                @if ($workspace->latitude && $workspace->longitude)
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $workspace->latitude }},{{ $workspace->longitude }}"
                       target="_blank" rel="noopener" style="color: var(--upwork-green); font-weight: 600;">
                        {{ $workspace->latitude }}, {{ $workspace->longitude }} (فتح الخريطة)
                    </a>
                @else
                    <div style="font-weight: 600;">—</div>
                @endif
            </div>
        </div>

        @if (!empty($workspace->amenities))
            <div style="margin-top: 16px;">
                <div style="font-size: 13px; color: var(--upwork-muted); margin-bottom: 6px;">المرافق</div>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @foreach ($workspace->amenities as $amenity)
                        <span style="padding: 4px 10px; background: var(--upwork-bg); border-radius: 16px; font-size: 13px;">{{ is_array($amenity) ? ($amenity['name'] ?? '') : $amenity }}</span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Owner --}}
    <div class="card" style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 16px;">بيانات المالك</h3>
        <div class="grid-2" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
            <div>
                <div style="font-size: 13px; color: var(--upwork-muted); margin-bottom: 4px;">الاسم</div>
                <div style="font-weight: 600;">{{ $workspace->workspaceOwner?->full_name ?? $workspace->owner?->full_name ?? '—' }}</div>
            </div>
            <div>
                <div style="font-size: 13px; color: var(--upwork-muted); margin-bottom: 4px;">رقم الهاتف</div>
                <div style="font-weight: 600; font-family: monospace;">{{ $workspace->workspaceOwner?->phone_number ?? $workspace->owner?->phone_number ?? '—' }}</div>
            </div>
            <div>
                <div style="font-size: 13px; color: var(--upwork-muted); margin-bottom: 4px;">واتساب</div>
                <div style="font-weight: 600; font-family: monospace;">{{ $workspace->admin_phone ?? '—' }}</div>
            </div>
        </div>
    </div>

    {{-- Decision --}}
    <div class="card" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-start;">
        <form method="POST" action="{{ route('admin.workspaces.approve', $workspace) }}"
              onsubmit="return confirm('تأكيد الموافقة على مساحة العمل وتفعيلها؟');">
            @csrf
            <button type="submit" class="btn-primary"
                    style="background: var(--upwork-green); color: #fff; border: none; padding: 12px 24px; border-radius: var(--radius-sm); font-weight: 700; cursor: pointer;">
                <i class="fa-solid fa-check"></i> الموافقة والتفعيل
            </button>
        </form>

        <form method="POST" action="{{ route('admin.workspaces.reject', $workspace) }}"
              onsubmit="return confirm('سيتم رفض الطلب وحذفه نهائيًا مع حساب المالك. هل أنت متأكد؟');"
              style="display: flex; gap: 8px; align-items: center; flex: 1; min-width: 280px;">
            @csrf
            <input type="text" name="reason" class="form-input" required maxlength="255"
                   placeholder="سبب الرفض (سيظهر للمالك)"
                   style="flex: 1; padding: 11px 14px; border: 1px solid var(--upwork-input-border); border-radius: var(--radius-sm);">
            <button type="submit"
                    style="background: var(--upwork-error); color: #fff; border: none; padding: 12px 24px; border-radius: var(--radius-sm); font-weight: 700; cursor: pointer; white-space: nowrap;">
                <i class="fa-solid fa-xmark"></i> رفض الطلب
            </button>
        </form>
    </div>
@endsection
