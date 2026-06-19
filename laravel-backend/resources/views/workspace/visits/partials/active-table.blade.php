<div id="active-visitors-card" data-pending="{{ $pendingCheckoutCount }}">
    <h3 style="margin:0 0 16px;">
        الحاضرون الآن ({{ $activeVisits->count() }})
        @if($pendingCheckoutCount > 0)
            <span style="background:#fff3d6; color:#8a6413; padding:4px 12px; border-radius:99px; font-size:13px; font-weight:800; margin-inline-start:8px;">
                <i class="fa-solid fa-bell"></i> {{ $pendingCheckoutCount }} بانتظار الموافقة على الخروج
            </span>
        @endif
    </h3>

    @if($activeVisits->isEmpty())
        <p style="text-align:center; color:var(--upwork-muted); padding:24px;">لا يوجد زوار بالداخل حاليًا.</p>
    @else
        <div class="table-responsive">
        <table style="width:100%; border-collapse:collapse; text-align:right;">
            <thead>
                <tr style="border-bottom:2px solid var(--upwork-border);">
                    <th style="padding:12px;">الزائر</th>
                    <th style="padding:12px;">الهاتف</th>
                    <th style="padding:12px;">الباقة</th>
                    <th style="padding:12px;">وقت الدخول</th>
                    <th style="padding:12px;">إجراء</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeVisits as $visit)
                    @php $isPending = $visit->checkout_requested_at !== null; @endphp
                    <tr style="border-bottom:1px solid var(--upwork-border); {{ $isPending ? 'background:#fff8e6; border-right:4px solid #f4a800;' : '' }}">
                        <td style="padding:12px; font-weight:bold;">
                            {{ $visit->visitor_name }}
                            @if($isPending)
                                <span class="checkout-req-badge" style="display:inline-flex; align-items:center; gap:5px; background:#f4a800; color:#3d2c00; padding:3px 9px; border-radius:99px; font-size:11px; font-weight:800; margin-inline-start:6px;">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    طلب خروج — {{ $visit->checkout_requested_at->locale('ar')->diffForHumans() }}
                                </span>
                            @endif
                        </td>
                        <td style="padding:12px; direction:ltr; text-align:right;">{{ $visit->user->phone_number ?? $visit->walkIn->phone_number ?? '—' }}</td>
                        <td style="padding:12px;">
                            @if(($visit->billing_source?->value ?? 'FREE') === 'FREE')
                                <span style="background:var(--upwork-green-soft); color:var(--upwork-green-dark); padding:4px 10px; border-radius:20px; font-weight:bold; font-size:13px;">مجاني</span>
                            @elseif($visit->billing_source?->value === 'GLOBAL_SUBSCRIPTION')
                                <span style="background:var(--upwork-blue); color:white; padding:4px 10px; border-radius:20px; font-weight:bold; font-size:13px;">عالمي</span>
                            @else
                                <span style="background:#ffb300; color:white; padding:4px 10px; border-radius:20px; font-weight:bold; font-size:13px;">اشتراك مساحة</span>
                            @endif
                        </td>
                        <td style="padding:12px;">
                            {{ $visit->check_in_at->diffForHumans() }}<br>
                            <small style="color:var(--upwork-muted);">{{ $visit->check_in_at->format('h:i A') }}</small>
                        </td>
                        <td style="padding:12px;">
                            <form action="{{ route('workspace.visits.checkout', $visit) }}" method="POST"
                                  onsubmit="return confirm('{{ $isPending ? 'الموافقة على طلب الخروج وتسجيل خروج الزائر؟' : 'تسجيل خروج هذا الزائر؟' }}');">
                                @csrf
                                @if($isPending)
                                    <button type="submit"
                                            style="padding:8px 16px; border-radius:var(--radius-sm); border:none; background:var(--upwork-green); color:#fff; cursor:pointer; font-weight:800;">
                                        <i class="fa-solid fa-check"></i> الموافقة وتسجيل الخروج
                                    </button>
                                @else
                                    <button type="submit"
                                            style="padding:8px 16px; border-radius:var(--radius-sm); border:1px solid var(--upwork-border); background:#fff; color:var(--upwork-slate); cursor:pointer; font-weight:700;">
                                        تسجيل خروج
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>
