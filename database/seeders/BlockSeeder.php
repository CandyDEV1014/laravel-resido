<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Block\Models\Block;
use Botble\Block\Models\BlockTranslation;
use Botble\Language\Models\LanguageMeta;

class BlockSeeder extends BaseSeeder
{
    public function run()
    {
        Block::truncate();
        BlockTranslation::truncate();
        LanguageMeta::where('reference_type', Block::class)->delete();

        $this->uploadFiles('block');
        $blocks = [
            [
                'name'        => 'Sign up',
                'alias'       => 'sign-up',
                'description' => '',
                'content'     => '
                                <div class="raw-html-embed">
                                    <section class="theme-bg call-to-act-wrap">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-lg-12">

                                                    <div class="call-to-act">
                                                        <div class="call-to-act-head">
                                                            <h3>Want to Become a Real Estate Agent?</h3>
                                                            <span>We\'ll help you to grow your career and growth.</span>
                                                        </div>
                                                        <a href="/register" class="btn btn-call-to-act">Sign Up Today</a>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                ',
            ],
            [
                'name'        => 'Download App',
                'alias'       => 'download-app',
                'description' => '',
                'content'     => '
                                <div class="raw-html-embed">
                                    <section class="bg-light">
                                        <div class="container">
                                            <div class="row align-items-center">
                                                <div class="col-lg-7 col-md-12 col-sm-12 content-column">
                                                    <div class="content_block_2">
                                                        <div class="content-box">
                                                        <div class="sec-title light">
                                                            <p class="text-blue">Download apps</p>
                                                            <h2>Download App Free App For Android And IPhone</h2>
                                                        </div>
                                                        <div class="text"><p></p></div>
                                                        <div class="btn-box clearfix mt-5">
                                                            <a href="" class="download-btn play-store"
                                                            ><i class="fab fa-google-play"></i> <span>Download on</span>
                                                            <h3>Google Play</h3></a
                                                            >
                                                            <a href="" class="download-btn app-store"
                                                            ><i class="fab fa-apple"></i> <span>Download on</span>
                                                            <h3>App Store</h3></a
                                                            >
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-12 col-sm-12 image-column">
                                                    <div class="image-box">
                                                        <figure class="image">
                                                        <img
                                                            src="/storage/banners/app.png"
                                                            alt="image"
                                                            class="img-fluid"
                                                        />
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                ',
            ],
            [
                'name'        => 'Download App Footer',
                'alias'       => 'download-app-footer',
                'description' => '',
                'content'     => '
                                <div class="raw-html-embed">
                                    <div class="footer-widget">
                                        <h4 class="widget-title">Download Apps</h4>
                                        <a href="#" class="other-store-link">
                                            <div class="other-store-app">
                                                <div class="os-app-icon">
                                                    <i class="lni-playstore theme-cl"></i>
                                                </div>
                                                <div class="os-app-caps">
                                                    Google Play
                                                    <span>Get It Now</span>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="other-store-link">
                                            <div class="other-store-app">
                                                <div class="os-app-icon">
                                                    <i class="lni-apple theme-cl"></i>
                                                </div>
                                                <div class="os-app-caps">
                                                    App Store
                                                    <span>Now it Available</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                ',
            ],
            [
                'name'        => 'How It Works?',
                'alias'       => 'how-it-works',
                'description' => '',
                'content'     => '
                                    <div class="raw-html-embed">
                                        <section>
                                            <div class="container">

                                                <div class="row justify-content-center">
                                                    <div class="col-lg-7 col-md-10 text-center">
                                                        <div class="sec-heading center">
                                                            <h2>How It Works?</h2>
                                                            <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4">
                                                        <div class="middle-icon-features-item">
                                                            <div class="icon-features-wrap"><div class="middle-icon-large-features-box f-light-success"><i class="ti-receipt text-success"></i></div></div>
                                                            <div class="middle-icon-features-content">
                                                                <h4>Evaluate Property</h4>
                                                                <p>There are many variations of passages of Lorem Ipsum available, but the majority have Ipsum available.</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4">
                                                        <div class="middle-icon-features-item">
                                                            <div class="icon-features-wrap"><div class="middle-icon-large-features-box f-light-warning"><i class="ti-user text-warning"></i></div></div>
                                                            <div class="middle-icon-features-content">
                                                                <h4>Meet Your Agent</h4>
                                                                <p>There are many variations of passages of Lorem Ipsum available, but the majority have Ipsum available.</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4">
                                                        <div class="middle-icon-features-item remove">
                                                            <div class="icon-features-wrap"><div class="middle-icon-large-features-box f-light-blue"><i class="ti-shield text-blue"></i></div></div>
                                                            <div class="middle-icon-features-content">
                                                                <h4>Close The Deal</h4>
                                                                <p>There are many variations of passages of Lorem Ipsum available, but the majority have Ipsum available.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </section>
                                    </div>',
            ],
            [
                'name'        => 'Achievement',
                'alias'       => 'achievement',
                'description' => '',
                'content'     => '
                                    <div class="raw-html-embed">
                                        <section>
                                            <div class="container">

                                                <div class="row justify-content-center">
                                                    <div class="col-lg-7 col-md-10 text-center">
                                                        <div class="sec-heading center mb-4">
                                                            <h2>Achievement</h2>
                                                            <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                                        <div class="achievement-wrap">
                                                            <div class="achievement-content">
                                                                <h4>20500+</h4>
                                                                <p>Completed Property</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                                        <div class="achievement-wrap">
                                                            <div class="achievement-content">
                                                                <h4>7600+</h4>
                                                                <p>Property Sales</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                                        <div class="achievement-wrap">
                                                            <div class="achievement-content">
                                                                <h4>12300+</h4>
                                                                <p>Apartment Rent</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                                        <div class="achievement-wrap">
                                                            <div class="achievement-content">
                                                                <h4>15200+</h4>
                                                                <p>Happy Clients</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </section>
                                        <div class="clearfix"></div>
                                    </div>',
            ],
            [
                'name'        => 'Our Story',
                'alias'       => 'our-story',
                'description' => '',
                'content'     => '
                                    <div class="raw-html-embed">
                                        <section>
                                            <div class="container">
                                                <div class="row align-items-center">

                                                    <div class="col-lg-6 col-md-6">
                                                        <img src="/storage/block/sb.png" class="img-fluid" alt="">
                                                    </div>

                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="story-wrap explore-content">

                                                            <h2>Our Story</h2>
                                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.</p>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                '
            ],
            [
                'name' => 'Our mission',
                'alias' => 'our-mission',
                'description' => '',
                'content' => '
                            <div class="raw-html-embed">
                                <section>
                                    <div class="container">

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="sec-heading center">
                                                    <h2>Our Mission &amp; Work Process</h2>
                                                    <p>Professional &amp; Dedicated Team</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">

                                            <div class="col-lg-6 col-md-6">

                                                <div class="icon-mi-left">
                                                    <i class="ti-lock theme-cl"></i>
                                                    <div class="icon-mi-left-content">
                                                        <h4>Fully Secure &amp; 24x7 Dedicated Support</h4>
                                                        <p>If you are an individual client, or just a business startup looking for good backlinks for your website.</p>
                                                    </div>
                                                </div>

                                                <div class="icon-mi-left">
                                                    <i class="ti-twitter theme-cl"></i>
                                                    <div class="icon-mi-left-content">
                                                        <h4>Manage your Social &amp; Busness Account Carefully</h4>
                                                        <p>If you are an individual client, or just a business startup looking for good backlinks for your website.</p>
                                                    </div>
                                                </div>

                                                <div class="icon-mi-left">
                                                    <i class="ti-layers theme-cl"></i>
                                                    <div class="icon-mi-left-content">
                                                        <h4>We are Very Hard Worker and loving</h4>
                                                        <p>If you are an individual client, or just a business startup looking for good backlinks for your website.</p>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-lg-6 col-md-6">
                                                <img src="/storage/block/vec-2.png" class="img-fluid" alt="">
                                            </div>

                                        </div>
                                    </div>
                                </section>
                            </div>
                '
            ]
        ];
        $translations = [
            [
                'name'        => 'Sign up',
                'description' => '',
                'content'     => '
                                <div class="raw-html-embed">
                                    <section class="theme-bg call-to-act-wrap">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-lg-12">

                                                    <div class="call-to-act">
                                                        <div class="call-to-act-head">
                                                            <h3>Want to Become a Real Estate Agent?</h3>
                                                            <span>We\'ll help you to grow your career and growth.</span>
                                                        </div>
                                                        <a href="/register" class="btn btn-call-to-act">Sign Up Today</a>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                ',
            ],
            [
                'name'        => 'Download App',
                'description' => '',
                'content'     => '
                                    <div class="raw-html-embed">
                                        <section class="bg-light">
                                            <div class="container">
                                                <div class="row align-items-center">
                                                    <div class="col-lg-7 col-md-12 col-sm-12 content-column">
                                                        <div class="content_block_2">
                                                            <div class="content-box">
                                                            <div class="sec-title light">
                                                                <p class="text-blue">Download apps</p>
                                                                <h2>Download App Free App For Android And IPhone</h2>
                                                            </div>
                                                            <div class="text"><p></p></div>
                                                            <div class="btn-box clearfix mt-5">
                                                                <a href="" class="download-btn play-store"
                                                                ><i class="fab fa-google-play"></i> <span>Download on</span>
                                                                <h3>Google Play</h3></a
                                                                >
                                                                <a href="" class="download-btn app-store"
                                                                ><i class="fab fa-apple"></i> <span>Download on</span>
                                                                <h3>App Store</h3></a
                                                                >
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-5 col-md-12 col-sm-12 image-column">
                                                        <div class="image-box">
                                                            <figure class="image">
                                                            <img
                                                                src="/storage/banners/app.png"
                                                                alt="image"
                                                                class="img-fluid"
                                                            />
                                                            </figure>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>

                ',
            ],
            [
                'name'        => 'Download App Footer',
                'description' => '',
                'content'     => '
                                    <div class="raw-html-embed">
                                        <div class="footer-widget">
                                            <h4 class="widget-title">Download Apps</h4>
                                            <a href="#" class="other-store-link">
                                                <div class="other-store-app">
                                                    <div class="os-app-icon">
                                                        <i class="lni-playstore theme-cl"></i>
                                                    </div>
                                                    <div class="os-app-caps">
                                                        Google Play
                                                        <span>Get It Now</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="other-store-link">
                                                <div class="other-store-app">
                                                    <div class="os-app-icon">
                                                        <i class="lni-apple theme-cl"></i>
                                                    </div>
                                                    <div class="os-app-caps">
                                                        App Store
                                                        <span>Now it Available</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                ',
            ],
            [
                'name'        => 'How It Works?',
                'description' => '',
                'content'     => '
                                <div class="raw-html-embed">
                                    <section>
                                        <div class="container">

                                            <div class="row justify-content-center">
                                                <div class="col-lg-7 col-md-10 text-center">
                                                    <div class="sec-heading center">
                                                        <h2>How It Works?</h2>
                                                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4 col-md-4">
                                                    <div class="middle-icon-features-item">
                                                        <div class="icon-features-wrap"><div class="middle-icon-large-features-box f-light-success"><i class="ti-receipt text-success"></i></div></div>
                                                        <div class="middle-icon-features-content">
                                                            <h4>Evaluate Property</h4>
                                                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have Ipsum available.</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-4">
                                                    <div class="middle-icon-features-item">
                                                        <div class="icon-features-wrap"><div class="middle-icon-large-features-box f-light-warning"><i class="ti-user text-warning"></i></div></div>
                                                        <div class="middle-icon-features-content">
                                                            <h4>Meet Your Agent</h4>
                                                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have Ipsum available.</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-4">
                                                    <div class="middle-icon-features-item remove">
                                                        <div class="icon-features-wrap"><div class="middle-icon-large-features-box f-light-blue"><i class="ti-shield text-blue"></i></div></div>
                                                        <div class="middle-icon-features-content">
                                                            <h4>Close The Deal</h4>
                                                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have Ipsum available.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </section>
                                </div>',
            ],
            [
                'name'        => 'Achievement',
                'description' => '',
                'content'     => '
                                <div class="raw-html-embed">
                                    <section>
                                        <div class="container">

                                            <div class="row justify-content-center">
                                                <div class="col-lg-7 col-md-10 text-center">
                                                    <div class="sec-heading center mb-4">
                                                        <h2>Achievement</h2>
                                                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-6">
                                                    <div class="achievement-wrap">
                                                        <div class="achievement-content">
                                                            <h4>20500+</h4>
                                                            <p>Completed Property</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-sm-6">
                                                    <div class="achievement-wrap">
                                                        <div class="achievement-content">
                                                            <h4>7600+</h4>
                                                            <p>Property Sales</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-sm-6">
                                                    <div class="achievement-wrap">
                                                        <div class="achievement-content">
                                                            <h4>12300+</h4>
                                                            <p>Apartment Rent</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-sm-6">
                                                    <div class="achievement-wrap">
                                                        <div class="achievement-content">
                                                            <h4>15200+</h4>
                                                            <p>Happy Clients</p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </section>
                                    <div class="clearfix"></div>
                                </div>',
            ],
            [
                'name'        => 'Our Story',
                'description' => '',
                'content'     => '
                                <div class="raw-html-embed">
                                    <section>
                                        <div class="container">
                                            <div class="row align-items-center">

                                                <div class="col-lg-6 col-md-6">
                                                    <img src="/storage/block/sb.png" class="img-fluid" alt="">
                                                </div>

                                                <div class="col-lg-6 col-md-6">
                                                    <div class="story-wrap explore-content">

                                                        <h2>Our Story</h2>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.</p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                '
            ],
            [
                'name'        => 'Our Story',
                'description' => '',
                'content'     => '
                                <div class="raw-html-embed">
                                    <section>
                                        <div class="container">
                                            <div class="row align-items-center">

                                                <div class="col-lg-6 col-md-6">
                                                    <img src="/storage/block/sb.png" class="img-fluid" alt="">
                                                </div>

                                                <div class="col-lg-6 col-md-6">
                                                    <div class="story-wrap explore-content">

                                                        <h2>Our Story</h2>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.</p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                '
            ],
            [
                'name' => 'Our mission',
                'description' => '',
                'content' => '
                            <div class="raw-html-embed">
                                <section>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="sec-heading center">
                                                    <h2>Our Mission &amp; Work Process</h2>
                                                    <p>Professional &amp; Dedicated Team</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="icon-mi-left">
                                                    <i class="ti-lock theme-cl"></i>
                                                    <div class="icon-mi-left-content">
                                                        <h4>Fully Secure &amp; 24x7 Dedicated Support</h4>
                                                        <p>If you are an individual client, or just a business startup looking for good backlinks for your website.</p>
                                                    </div>
                                                </div>
                                                <div class="icon-mi-left">
                                                    <i class="ti-twitter theme-cl"></i>
                                                    <div class="icon-mi-left-content">
                                                        <h4>Manage your Social &amp; Busness Account Carefully</h4>
                                                        <p>If you are an individual client, or just a business startup looking for good backlinks for your website.</p>
                                                    </div>
                                                </div>
                                                <div class="icon-mi-left">
                                                    <i class="ti-layers theme-cl"></i>
                                                    <div class="icon-mi-left-content">
                                                        <h4>We are Very Hard Worker and loving</h4>
                                                        <p>If you are an individual client, or just a business startup looking for good backlinks for your website.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <img src="/storage/block/vec-2.png" class="img-fluid" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                '
            ]
        ];
        foreach ($blocks as $index => $block) {
            $block = Block::create($block);
        }
        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['blocks_id'] = $index + 1;
            BlockTranslation::insert($item);
        }
    }
}
