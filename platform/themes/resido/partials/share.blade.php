<div class="post-share">
    <h4 class="pbm-title">{{ $title }}:</h4>
    <ul class="list">
        <li>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}&title={{ $description }}"
                target="_blank" title="{{ __('Share on Facebook') }}" rel="nofollow"><i class="fab fa-facebook-f"></i></a>
        </li>
        <li>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&summary={{ rawurldecode($description) }}&source=Linkedin"
                title="{{ __('Share on Linkedin') }}" target="_blank" rel="nofollow"><i class="fab fa-linkedin-in"></i></a>
        </li>
        <li>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ $description }}"
                target="_blank" title="{{ __('Share on Twitter') }}" rel="nofollow"><i class="fab fa-twitter"></i></a>
        </li>
    </ul>
</div>
