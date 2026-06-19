@extends('admin.layout.app')

@section('title', 'أكواد الباقات | بوابة الإدارة')
@section('header', 'إدارة أكواد الباقات')

@section('content')
<div class="alert alert-success">
    <i class="fa-solid fa-circle-info"></i>
    <div>يتم بيع باقات Silver وGold يدوياً بعد استلام المبلغ نقداً، ثم يتم إرسال كود التفعيل للعميل. الدفع البنكي غير مفعل حالياً.</div>
</div>
<div class="grid-4">
    <div class="card" style="grid-column: span 1;">
        <h3 class="card-title" style="font-size: 16px;">توليد أكواد جديدة</h3>
        
        <form action="{{ route('admin.plancodes.store') }}" method="POST">
            @csrf
            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: var(--upwork-slate); margin-bottom: 8px;">الباقة</label>
                <select name="plan_id" class="form-select" required>
                    <option value="" disabled selected>-- اختر الباقة --</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }} ({{ $plan->tier->value ?? $plan->tier }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: var(--upwork-slate); margin-bottom: 8px;">الكمية</label>
                <input type="number" name="count" value="10" min="1" max="50" class="form-input">
            </div>
            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: var(--upwork-slate); margin-bottom: 8px;">صلاحية الكود (بالأيام)</label>
                <input type="number" name="expires_in_days" placeholder="مثال: 30" min="1" class="form-input" style="margin-bottom: 4px;">
                <p style="font-size: 12px; color: var(--upwork-muted); margin-bottom: 16px;">اتركه فارغاً لعدم تحديد مدة انتهاء</p>
            </div>
            <button type="submit" class="btn-primary" style="width: 100%;">
                توليد الأكواد
            </button>
        </form>
    </div>

    <div class="card" style="grid-column: span 3; padding: 0;">
        <div class="table-responsive">
            <table>
                <thead style="background-color: var(--upwork-bg);">
                    <tr>
                        <th>الكود</th>
                        <th>الباقة</th>
                        <th>الحالة</th>
                        <th>الانتهاء</th>
                        <th style="text-align: left;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($codes as $code)
                    <tr style="transition: var(--transition);">
                        <td style="font-family: monospace; font-weight: 700; color: var(--upwork-slate);">{{ $code->code }}</td>
                        <td>{{ $code->plan?->name ?? 'غير معروف' }}</td>
                        <td>
                            @if($code->status === 'ACTIVE')
                                <span style="padding: 4px 8px; background-color: var(--upwork-success-bg); color: var(--upwork-success); border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;">نشط</span>
                            @elseif($code->status === 'REDEEMED')
                                <span style="padding: 4px 8px; background-color: #f1f5f9; color: #475569; border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;">مستخدم</span>
                            @elseif($code->status === 'VOIDED')
                                <span style="padding: 4px 8px; background-color: var(--upwork-error-bg); color: var(--upwork-error); border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;">ملغى</span>
                            @endif
                        </td>
                        <td style="font-size: 13px; color: var(--upwork-muted);">
                            {{ $code->expires_at ? $code->expires_at->format('Y-m-d') : 'لا ينتهي' }}
                        </td>
                        <td style="text-align: left;">
                            @if($code->status === 'ACTIVE')
                            <form action="{{ route('admin.plancodes.revoke', $code) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الكود؟');">
                                @csrf
                                <button type="submit" style="background: none; border: none; color: var(--upwork-error); font-weight: 600; cursor: pointer; font-family: inherit; font-size: 14px;">إلغاء</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--upwork-muted);">
                            لم يتم توليد أي أكواد بعد.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($codes->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--upwork-border);">
            {{ $codes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
