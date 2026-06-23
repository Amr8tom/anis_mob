@if($items->isEmpty())
    <p class="education-empty">{{ $empty }}</p>
@else
    <div class="education-simple-list">
        @foreach($items as $item)
            <div class="education-simple-item">
                <div>
                    <strong>{{ $item->name }}</strong>
                    <span class="education-status {{ $item->is_active ? 'is-active' : 'is-paused' }}" style="margin-inline-start:8px;">{{ $item->is_active ? __('portal.education.status_active') : __('portal.education.status_paused') }}</span>
                </div>
                <form action="{{ route($routeName, $item) }}" method="POST">
                    @csrf
                    <button type="submit" class="education-link-btn">{{ $item->is_active ? __('portal.education.deactivate') : __('portal.education.activate') }}</button>
                </form>
            </div>
        @endforeach
    </div>
@endif
