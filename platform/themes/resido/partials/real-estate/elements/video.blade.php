@php
    $video = $object->getMetaData('video', true);
    $videoUrl = $video['url'] ?? '';
@endphp
@if (!empty($videoUrl))
    <div class="property_block_wrap style-2">
        <div class="property_block_wrap_header">
            <a data-bs-toggle="collapse" data-parent="#vid" data-bs-target="#clFour" aria-controls="clFour"
                href="javascript:void(0);" aria-expanded="true" class="collapsed">
                <h4 class="property_block_title">{{ __('Property video') }}</h4>
            </a>
        </div>

        <div id="clFour" class="panel-collapse collapse">
            <div class="block-body">
                <div class="property_video">
                    <div class="thumb">
                        <img class="pro_img w-100" src="{{ get_image_from_video_property($object) }}"
                            alt="{{ $object->name }}">
                        <div class="overlay_icon">
                            <div class="bb-video-box">
                                <div class="bb-video-box-inner">
                                    <div class="bb-video-box-innerup">
                                        <a href="{{ $videoUrl }}" id="popup-youtube"
                                            class="theme-cl popup-youtube"><i class="ti-control-play"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
