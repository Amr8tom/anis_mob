<div id="active-visitors-card" data-pending="{{ $pendingCheckoutCount }}">
    <h3 style="margin:0 0 16px; color:var(--vd-text); display:flex; align-items:center; flex-wrap:wrap; gap:8px;">
        الحاضرون الآن ({{ $activeVisits->count() }})
        @if($pendingCheckoutCount > 0)
            <span style="background:rgba(245,158,11,0.16); color:#fcd34d; padding:4px 12px; border-radius:99px; font-size:13px; font-weight:800; border:1px solid rgba(245,158,11,0.35);">
                <i class="fa-solid fa-bell"></i> {{ $pendingCheckoutCount }} بانتظار الموافقة على الخروج
            </span>
        @endif
    </h3>

    @if($activeVisits->isEmpty())
        <p style="text-align:center; color:var(--vd-text-muted); padding:24px;">لا يوجد زوار بالداخل حاليًا.</p>
    @else
        <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>الزائر</th>
                    <th>الهاتف</th>
                    <th>الباقة</th>
                    <th>وقت الدخول</th>
                    <th>إجراء</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeVisits as $visit)
                    @php $isPending = $visit->checkout_requested_at !== null; @endphp
                    <tr @style(['border-right:4px solid #f59e0b; background:rgba(245,158,11,0.08)' => $isPending])>
                        <td style="font-weight:700;">
                            {{ $visit->visitor_name }}
                            @if($isPending)
                                <span class="checkout-req-badge" style="display:inline-flex; align-items:center; gap:5px; background:#f59e0b; color:#451a03; padding:3px 9px; border-radius:99px; font-size:11px; font-weight:800; margin-inline-start:6px;">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    طلب خروج — {{ $visit->checkout_requested_at->locale('ar')->diffForHumans() }}
                                </span>
                            @endif
                        </td>
                        <td style="direction:ltr; text-align:right;" class="time">{{ $visit->user->phone_number ?? $visit->walkIn->phone_number ?? '—' }}</td>
                        <td>
                            @if(($visit->billing_source?->value ?? 'FREE') === 'FREE')
                                <span class="badge badge-free">مجاني</span>
                            @elseif($visit->billing_source?->value === 'GLOBAL_SUBSCRIPTION')
                                <span class="badge badge-global">عالمي</span>
                            @else
                                <span class="badge badge-space">اشتراك مساحة</span>
                            @endif
                        </td>
                        <td>
                            <span style="color:var(--vd-accent); font-weight:700;">{{ $visit->check_in_at->diffForHumans() }}</span><br>
                            <small class="time">{{ $visit->check_in_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <form action="{{ route('workspace.visits.checkout', $visit) }}" method="POST"
                                  onsubmit="return confirm('{{ $isPending ? 'الموافقة على طلب الخروج وتسجيل خروج الزائر؟' : 'تسجيل خروج هذا الزائر؟' }}');">
                                @csrf
                                @if($isPending)
                                    <button type="submit"
                                            style="padding:8px 16px; border-radius:var(--radius-sm); border:none; background:var(--vd-accent); color:#06210f; cursor:pointer; font-weight:800;">
                                        <i class="fa-solid fa-check"></i> الموافقة وتسجيل الخروج
                                    </button>
                                @else
                                    <button type="submit" class="vd-btn">
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
