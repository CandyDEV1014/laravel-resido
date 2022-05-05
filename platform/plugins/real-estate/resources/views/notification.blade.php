<li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
    <a href="javascript:;" class="dropdown-toggle dropdown-header-name" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="icon-envelope-open"></i>
        <span class="badge badge-default"> {{ count($consults) }} </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-right">
        <li class="external">
            <h3>{!! trans('plugins/real-estate::consult.new_consult_notice', ['count' => count($consults)]) !!}</h3>
            <a href="{{ route('consult.index') }}">{{ trans('plugins/real-estate::consult.view_all') }}</a>
        </li>
        <li>
            <ul class="dropdown-menu-list scroller" style="height: {{ count($consults) * 70 }}px;" data-handle-color="#637283">
                @foreach($consults as $consult)
                    <li>
                        <a href="{{ route('consult.edit', $consult->id) }}">
                            <span class="photo">
                                <img src="{{ \Botble\Base\Supports\Gravatar::image($consult->email) }}" class="rounded-circle" alt="{{ $consult->name }}">
                            </span>
                            <span class="subject"><span class="from"> {{ $consult->name }} </span><span class="time">{{ $consult->created_at->toDateTimeString() }} </span></span>
                            <span class="message"> {{ $consult->phone }} - {{ $consult->email }} </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
</li>
