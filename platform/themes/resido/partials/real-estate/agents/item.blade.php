<div class="agents-grid">
    <div class="agents-grid-wrap">
        <div class="fr-grid-thumb">
            @if ($account->username)
                <a href="{{ route('public.agent', $account->username) }}">
                    <img src="{{ $account->avatar_url }}" class="img-fluid mx-auto" alt="{{ $account->name }}">
                </a>
            @else
                <img src="{{ $account->avatar_url }}" class="img-fluid mx-auto" alt="{{ $account->name }}">
            @endif
        </div>

        <div class="fr-grid-deatil">
            <div class="fr-grid-deatil-flex">
                <h5 class="fr-can-name">
                    @if ($account->username)
                        <a href="{{ route('public.agent', $account->username) }}">{{ $account->name }}</a>
                    @else
                        {{ $account->name }}
                    @endif
                </h5>
            </div>
            <div class="fr-grid-deatil-flex-right">
                <div class="agent-email"><a href="mailto:{{ $account->email }}"><i class="ti-email"></i></a></div>
            </div>
        </div>
    </div>

    <div class="fr-grid-info">
        <ul>
            @if ($account->phone)
                <li><strong>{{ __('Phone') }}:</strong> {{ $account->phone }}</li>
            @endif

            <li><strong>{{ __('Email') }}:</strong> {{ $account->email }}</li>
        </ul>
    </div>

    <div class="fr-grid-footer">
        <div class="fr-grid-footer-flex">
            <span class="fr-position"><i class="fa fa-home"></i>{{ __(':count properties', ['count' => $account->properties_count]) }}</span>
        </div>
        @if ($account->username)
            <div class="fr-grid-footer-flex-right">
                <a href="{{ route('public.agent', $account->username) }}" class="prt-view" tabindex="0">{{ __('View') }}</a>
            </div>
        @endif
    </div>

</div>
