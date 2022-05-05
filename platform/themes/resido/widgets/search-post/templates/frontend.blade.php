<!-- Categories -->
<div class="single-widgets widget_search">
    <h4 class="title">{{ $config['name'] }}</h4>
    <form action="{{ url('/news') }}" class="form-search-blog" method="get">
        <div class="input-group">
            <input type="text" class="form-control" id="search" name="q" placeholder="Enter keyword...">
            <div class="input-group-append">
                <button class="btn btn-primary btn-search" type="button">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>

