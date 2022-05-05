<div class="col-sm-4">
    <div class="footer-widget">
        <h4 class="widget-title">{{ $config['name'] }}</h4>
        {!!
            Menu::generateMenu(['slug' => $config['menu_id']])
        !!}
    </div>
</div>
