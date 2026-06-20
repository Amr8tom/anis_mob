@extends('workspace.layouts.app')

@section('title', 'الجلسات الدراسية | بوابة مساحة العمل')

@section('styles')
@include('workspace.partials.dark-theme')
@endsection

@section('content')
<div class="visits-dark">

    {{-- Hero header --}}
    <div class="vd-card vd-card--accent" style="margin-bottom:22px;">
        <div class="vd-header">
            <div style="display:flex; align-items:center; gap:12px;">
                <div class="vd-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                <div>
                    <h3 style="font-size:20px;">الجلسات الدراسية وورش العمل</h3>
                    <p>أنشئ جلساتك وورش العمل وأدِر مواعيدها وأسعارها.</p>
                </div>
            </div>
            <a href="{{ route('workspace.sessions.create') }}" class="btn-primary" style="text-decoration:none;">
                <i class="fa-solid fa-plus"></i> إضافة جلسة جديدة
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="vd-card vd-card--pad" style="margin-bottom:16px; border-right:4px solid var(--vd-accent); color:var(--vd-accent);">{{ session('success') }}</div>
    @endif

    {{-- Sessions table --}}
    <div class="vd-card">
        <div class="vd-body">
        @if($sessions->count() > 0)
            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>عنوان الجلسة</th>
                        <th>المدرب / المحاضر</th>
                        <th>التوقيت</th>
                        <th>السعر</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                        @php $priceEgp = $session->price_cents / 100; @endphp
                        <tr>
                            <td style="font-weight:700;">{{ $session->title }}</td>
                            <td class="{{ $session->instructor_name ? '' : 'dim' }}">{{ $session->instructor_name ?? 'غير محدد' }}</td>
                            <td>
                                <span class="time">{{ \Carbon\Carbon::parse($session->start_time)->format('Y-m-d h:i A') }}</span><br>
                                <small class="dim">إلى: {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}</small>
                            </td>
                            <td>
                                @if($priceEgp > 0)
                                    <span class="money">{{ number_format($priceEgp, 2) }} <small style="font-size:11px;">ج.م</small></span>
                                @else
                                    <span class="badge badge-free">مجاني</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                                    <a href="{{ route('workspace.sessions.edit', $session) }}" class="vd-btn"><i class="fa-solid fa-pen"></i> تعديل</a>
                                    <form action="{{ route('workspace.sessions.destroy', $session) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الجلسة؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="vd-btn vd-btn-danger"><i class="fa-solid fa-trash"></i> حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            <div style="margin-top:20px;">
                {{ $sessions->links('vendor.pagination.upwork') }}
            </div>
        @else
            <div class="empty-state">
                <i class="fa-solid fa-chalkboard-user"></i>
                لا توجد جلسات حالياً. أضف جلستك الأولى الآن!
            </div>
        @endif
        </div>
    </div>
</div>
@endsection
