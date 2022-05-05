@php Theme::layout('homepage'); @endphp

<div class="container">
    <div style="margin: 40px 0;">
        <h4 style="color: #f00; margin-bottom: 15px;">You need to setup your homepage first!</h4>

        <p><strong>1. Go to Admin -> Plugins then activate all plugins.</strong></p>
        <p><strong>2. Go to Admin -> Pages and create a page:</strong></p>

        <div style="margin: 20px 0;">
            <div>- Content:</div>
            <div style="border: 1px solid rgba(0,0,0,.1); padding: 10px; margin-top: 10px;">
                <div>[hero-banner title="Find accessible homes to rent" bg="banners/banner-1.jpg"][/hero-banner]</div>
                <div>[static-block alias="how-it-works"][/static-block]</div>
                <div>[featured-properties title="Explore Good Places" limit="6" style="2"]At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores[/featured-properties]</div>
                <div>[properties-by-locations title="Find By Locations"]At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores[/properties-by-locations]</div>
                <div>[testimonials title="Good Reviews By Customers" description="At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores"][/testimonials]</div>
                <div>[our-packages title="See Our Packages" description="At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores"][/our-packages]</div>
                <div>[recently-viewed-properties title="Recently Viewed Properties" subtitle="Your currently viewed properties."][/recently-viewed-properties]</div>
            </div>
            <br>
            <div>- Template: <strong>Homepage</strong>.</div>
        </div>

        <p><strong>3. Then go to Admin -> Appearance -> Theme options -> Page to set your homepage.</strong></p>
    </div>
</div>
