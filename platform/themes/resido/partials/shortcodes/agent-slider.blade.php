<!-- ============================ Latest Property For Sale Start ================================== -->
<section class="pt-7 property-bg-custom">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-lg-7 col-md-10 text-center">
            <div class="sec-heading center mb-4">
               <h2>{!! clean($title) !!}</h2>
               <p>{!! clean($description) !!}</p>
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-lg-12 col-md-12">
            @if ($myBlock->count())
                @php
                    $count = 1
                @endphp
            
                @foreach($myBlock as $account)
                    @if ($count % 4 == 1) 
                    <div class="property-slide" style="max-height: 554px;">
                    @endif
                        <!-- Single Item -->
                        <div class="single-items">
                  
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

                        </div>
                    @if ($count % 4 == 0) 
                    </div>
                    @endif
                    @php
                        $count++;
                    @endphp
                @endforeach

                @if ($count % 4 != 1) 
                    </div>
                @endif
            @else
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                        <p class="item">{{ __('0 results') }}</p>
                    </div>
                </div>
            @endif
         </div>
      </div>
      <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                <a href="{{ url('agents') }}" class="btn btn-theme-light-2 rounded">{{ __('Browse More Agents') }}</a>
            </div>
        </div>
   </div>
</section>
<!-- ============================ Latest Property For Sale End ================================== -->
