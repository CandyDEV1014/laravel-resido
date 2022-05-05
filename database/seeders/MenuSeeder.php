<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Blog\Models\Post;
use Botble\Language\Models\LanguageMeta;
use Botble\Menu\Models\Menu as MenuModel;
use Botble\Menu\Models\MenuLocation;
use Botble\Menu\Models\MenuNode;
use Botble\Page\Models\Page;
use Botble\RealEstate\Models\Property;
use Illuminate\Support\Arr;
use Menu;

class MenuSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'en_US' => [
                [
                    'name'     => 'Main menu',
                    'slug'     => 'main-menu',
                    'location' => 'main-menu',
                    'items'    => [
                        [
                            'title'    => 'Home',
                            'url'      => '/',
                            'children' => [
                                [
                                    'title' => 'Home layout 1',
                                    'url'   => '/',
                                ],
                                [
                                    'title'          => 'Home layout 2',
                                    'reference_id'   => 2,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Home layout 3',
                                    'reference_id'   => 3,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Home layout 4',
                                    'reference_id'   => 4,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Home layout 5',
                                    'reference_id'   => 5,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Home layout 6',
                                    'reference_id'   => 6,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Home layout 7',
                                    'reference_id'   => 7,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Home layout 8',
                                    'reference_id'   => 8,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Home layout 9',
                                    'reference_id'   => 9,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Map home layout',
                                    'reference_id'   => 10,
                                    'reference_type' => Page::class,
                                ],
                            ],
                        ],
                        [
                            'title'    => 'Listings',
                            'url'   => '/properties',
                            'children' => [
                                [
                                    'title'    => 'List Layout',
                                    'url'   => '/properties?layout=sidebar',
                                    'children' => [
                                        [
                                            'title' => 'With Sidebar',
                                            'url'   => '/properties?layout=sidebar',
                                        ],
                                        [
                                            'title' => 'With Map',
                                            'url'   => '/properties?layout=map',
                                        ],
                                        [
                                            'title' => 'Full width',
                                            'url'   => '/properties?layout=full',
                                        ],
                                    ],
                                ],
                                [
                                    'title'    => 'Grid Layout',
                                    'url'   => '/properties?layout=grid_sidebar',
                                    'children' => [
                                        [
                                            'title' => 'With Sidebar',
                                            'url'   => '/properties?layout=grid_sidebar',
                                        ],
                                        [
                                            'title' => 'With Map',
                                            'url'   => '/properties?layout=grid_map',
                                        ],
                                        [
                                            'title' => 'Full width',
                                            'url'   => '/properties?layout=grid_full',
                                        ],
                                    ],
                                ],
                                [
                                    'title' => 'Half Map Search',
                                    'url'   => '/properties?layout=half_map',
                                ],
                            ],
                        ],
                        [
                            'title'    => 'Features',
                            'url'      => '/',
                            'children' => [
                                [
                                    'title'    => 'Single Property',
                                    'url'      => '/',
                                    'children' => [
                                        [
                                            'title'          => 'Single Property 1',
                                            'reference_id'   => 1,
                                            'reference_type' => Property::class,
                                        ],
                                        [
                                            'title'          => 'Single Property 2',
                                            'reference_id'   => 2,
                                            'reference_type' => Property::class,
                                        ],
                                        [
                                            'title'          => 'Single Property 3',
                                            'reference_id'   => 3,
                                            'reference_type' => Property::class,
                                        ],
                                    ],
                                ],
                                [
                                    'title'    => 'Agents',
                                    'url'      => '/',
                                    'children' => [
                                        [
                                            'title'          => 'Agents List',
                                            'url'            => '/agents',
                                        ],
                                        [
                                            'title'          => 'Agents Detail Page',
                                            'url'            => '/agents/thesky9',
                                        ]
                                    ],
                                ],
                                [
                                    'title'    => 'My Account',
                                    'url'      => '/',
                                    'children' => [
                                        [
                                            'title'          => 'User Dashboard',
                                            'url'            => '/account/dashboard',
                                        ],
                                        [
                                            'title'          => 'Properties',
                                            'url'            => '/account/properties',
                                        ],
                                        [
                                            'title'          => 'My Profile',
                                            'url'            => '/account/settings',
                                        ],
                                        [
                                            'title'          => 'Packages',
                                            'url'            => '/account/packages',
                                        ],
                                        [
                                            'title'          => 'Change Password',
                                            'url'            => '/account/security',
                                        ],
                                    ],
                                ],
                                [
                                    'title'          => 'Submit Property',
                                    'url'            => '/account/properties/create',
                                ],
                            ],
                        ],
                        [
                            'title'    => 'Pages',
                            'url'      => '/',
                            'children' => [
                                [
                                    'title'          => 'Blogs Page',
                                    'reference_id'   => 12,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Blog Detail',
                                    'reference_id'   => 1,
                                    'reference_type' => Post::class,
                                ],
                                [
                                    'title'          => 'Pricing',
                                    'url'   => '/home#pricing-section',
                                ],
                                [
                                    'title'          => 'Error Page',
                                    'url'            => '/error-page',
                                    'reference_id'   => 39,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Contact',
                                    'reference_id'   => 14,
                                    'reference_type' => Page::class,
                                ],
                            ],

                        ],
                        [
                            'title' => 'Sign Up',
                            'url'   => '/register',
                        ],
                    ],
                ],
                [
                    'name'  => 'About',
                    'slug'  => 'about',
                    'items' => [
                        [
                            'title'          => 'About us',
                            'reference_id'   => 13,
                            'reference_type' => Page::class,
                        ],
                        [
                            'title'          => 'Contact us',
                            'reference_id'   => 14,
                            'reference_type' => Page::class,
                        ],
                        [
                            'title'          => 'Terms & Conditions',
                            'reference_id'   => 15,
                            'reference_type' => Page::class,
                        ],
                    ],
                ],
                [
                    'name'  => 'More information',
                    'slug'  => 'more-information',
                    'items' => [
                        [
                            'title' => 'All properties',
                            'url'   => '/properties',
                        ],
                        [
                            'title' => 'Houses for sale',
                            'url'   => '/properties?type=sale',
                        ],
                        [
                            'title' => 'Houses for rent',
                            'url'   => '/properties?type=rent',
                        ],
                    ],
                ],
                [
                    'name'  => 'News',
                    'slug'  => 'news',
                    'items' => [
                        [
                            'title'          => 'Latest news',
                            'url'            => '/news',
                        ],
                        [
                            'title'          => 'House architecture',
                            'url'            => '/house-architecture',
                        ],
                        [
                            'title'          => 'House design',
                            'url'            => '/house-design',
                        ],
                        [
                            'title'          => 'Building materials',
                            'url'            => '/building-materials',
                        ],
                    ],
                ],
            ],
            'vi'    => [
                [
                    'name'     => 'Menu chính',
                    'slug'     => 'menu-chinh',
                    'location' => 'main-menu',
                    'items'    => [
                        [
                            'title'    => 'Trang chủ',
                            'reference_id'   => 1,
                            'reference_type' => Page::class,
                            'children' => [
                                [
                                    'title' => 'Trang chủ 1',
                                    'reference_id'   => 1,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Trang chủ 2',
                                    'reference_id'   => 2,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Trang chủ 3',
                                    'reference_id'   => 3,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Trang chủ 4',
                                    'reference_id'   => 4,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Trang chủ 5',
                                    'reference_id'   => 5,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Trang chủ 6',
                                    'reference_id'   => 6,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Trang chủ 7',
                                    'reference_id'   => 7,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Trang chủ 8',
                                    'reference_id'   => 8,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Trang chủ 9',
                                    'reference_id'   => 9,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Trang chủ bản đồ',
                                    'reference_id'   => 10,
                                    'reference_type' => Page::class,
                                ],
                            ],
                        ],
                        [
                            'title'    => 'Danh sách',
                            'url'   => '/properties?layout=sidebar',
                            'children' => [
                                [
                                    'title'    => 'Dạng list',
                                    'url'   => '/properties?layout=sidebar',
                                    'children' => [
                                        [
                                            'title' => 'Có thanh bên',
                                            'url'   => '/properties?layout=sidebar',
                                        ],
                                        [
                                            'title'        => 'Chiều rộng đầy đủ',
                                            'url'          => '/properties?layout=full',
                                        ],
                                        [
                                            'title'        => 'Có Bản Đồ',
                                            'url'          => '/properties?layout=map',
                                        ],
                                    ],
                                ],
                                [
                                    'title'    => 'Dạng cột',
                                    'url'   => '/properties?layout=grid_sidebar',
                                    'children' => [
                                        [
                                            'title' => 'Có thanh bên',
                                            'url'   => '/properties?layout=grid_sidebar',
                                        ],
                                        [
                                            'title' => 'Có Map',
                                            'url'   => '/properties?layout=grid_map',
                                        ],
                                        [
                                            'title' => 'Chiều rộng đầy đủ',
                                            'url'   => '/properties?layout=grid_full',
                                        ],
                                    ],
                                ],
                                [
                                    'title' => 'Có 1/2 bản đồ',
                                    'url'   => '/properties?layout=half_map',
                                ],
                            ],
                        ],
                        [
                            'title'    => 'Tính năng',
                            'children' => [
                                [
                                    'title'    => 'Chi tiết bất động sản',
                                    'reference_id'   => 21,
                                    'reference_type' => Property::class,
                                    'children' => [
                                        [
                                            'title'          => 'Chi tiết bất động sản 1',
                                            'reference_id'   => 21,
                                            'reference_type' => Property::class,
                                        ],
                                        [
                                            'title'          => 'Chi tiết bất động sản 2',
                                            'reference_id'   => 22,
                                            'reference_type' => Property::class,
                                        ],
                                        [
                                            'title'          => 'Chi tiết bất động sản 3',
                                            'reference_id'   => 23,
                                            'reference_type' => Property::class,
                                        ],
                                    ],
                                ],
                                [
                                    'title'    => 'Đại lý và người đại lý',
                                    'url'      => '#',
                                    'children' => [
                                        [
                                            'title'          => 'Danh sách đại lý',
                                            'url'            => '/agents',
                                        ],
                                        [
                                            'title'          => 'Chi tiết địa lý',
                                            'url'            => '/agents/thesky9',
                                        ]
                                    ],
                                ],
                                [
                                    'title'    => 'Tài khoản',
                                    'url'      => '#',
                                    'children' => [
                                        [
                                            'title'          => 'My Dashboard',
                                            'url'            => '/account/dashboard',
                                        ],
                                        [
                                            'title'          => 'Properties',
                                            'url'            => '/account/properties',
                                        ],
                                        [
                                            'title'          => 'My Profile',
                                            'url'            => '/account/settings',
                                        ],
                                        [
                                            'title'          => 'Packages',
                                            'url'            => '/account/packages',
                                        ],
                                        [
                                            'title'          => 'Đổi mật khẩu',
                                            'url'            => '/account/security',
                                        ],
                                    ]
                                ],
                                [
                                    'title'          => 'Đăng bất động sản',
                                    'url'            => '/account/properties/create',
                                ],
                            ],
                        ],
                        [
                            'title'    => 'Trang',
                            'url'      => '/',
                            'children' => [
                                [
                                    'title'          => 'Trang blog',
                                    'reference_id'   => 28,
                                    'reference_type' => Page::class,
                                ],
                                [
                                    'title'          => 'Bài viết chi tiết',
                                    'reference_id'   => 20,
                                    'reference_type' => Post::class,
                                ],
                                [
                                    'title'          => 'Bảng giá',
                                    'url'            => '/home#pricing-section',
                                ],
                                [
                                    'title'          => 'Page lỗi',
                                    'url'            => '/error-page',
                                ],
                                [
                                    'title'          => 'Liên hệ',
                                    'reference_id'   => 30,
                                    'reference_type' => Page::class,
                                ],
                            ],

                        ],
                        [
                            'title' => 'Đăng ký',
                            'url'   => '/register',
                        ],
                    ],
                ],
                [
                    'name'  => 'Về chúng tôi',
                    'slug'  => 've-chung-toi',
                    'items' => [
                        [
                            'title'          => 'Về chúng tôi',
                            'reference_id'   => 9,
                            'reference_type' => Page::class,
                        ],
                        [
                            'title'          => 'Liên hệ',
                            'reference_id'   => 10,
                            'reference_type' => Page::class,
                        ],
                        [
                            'title'          => 'Điều khoản và quy định',
                            'reference_id'   => 11,
                            'reference_type' => Page::class,
                        ],
                    ],
                ],
                [
                    'name'  => 'Thông tin thêm',
                    'slug'  => 'thong-tin-them',
                    'items' => [
                        [
                            'title' => 'Nhà - Căn hộ',
                            'url'   => '/properties',
                        ],
                        [
                            'title' => 'Nhà bán',
                            'url'   => '/properties?type=sale',
                        ],
                        [
                            'title' => 'Nhà cho thuê',
                            'url'   => '/properties?type=rent',
                        ],
                    ],
                ],
                [
                    'name'  => 'Tin tức',
                    'slug'  => 'tin-tuc',
                    'items' => [
                        [
                            'title'          => 'Tin tức mới nhất',
                            'reference_id'   => 2,
                            'reference_type' => Page::class,
                        ],
                        [
                            'title'          => 'Kiến trúc nhà',
                            'url'            => '/kien-truc-nha',
                        ],
                        [
                            'title'          => 'Thiết kế nhà',
                            'url'            => '/thiet-ke-nha',
                        ],
                        [
                            'title'          => 'Vật liệu xây dựng',
                            'url'            => '/vat-lieu-xay-dung',
                        ],
                    ],
                ],
            ],
        ];

        MenuModel::truncate();
        MenuLocation::truncate();
        MenuNode::truncate();
        LanguageMeta::where('reference_type', MenuModel::class)->delete();
        LanguageMeta::where('reference_type', MenuLocation::class)->delete();

        foreach ($data as $locale => $menus) {
            foreach ($menus as $index => $item) {
                $menu = MenuModel::create(Arr::except($item, ['items', 'location']));

                if (isset($item['location'])) {
                    $menuLocation = MenuLocation::create([
                        'menu_id'  => $menu->id,
                        'location' => $item['location'],
                    ]);

                    $originValue = LanguageMeta::where([
                        'reference_id'   => $locale == 'en_US' ? 1 : 2,
                        'reference_type' => MenuLocation::class,
                    ])->value('lang_meta_origin');

                    LanguageMeta::saveMetaData($menuLocation, $locale, $originValue);
                }

                foreach ($item['items'] as $menuNode) {
                    $this->createMenuNode($index, $menuNode, $locale, $menu->id);
                }

                $originValue = null;

                if ($locale !== 'en_US') {
                    $originValue = LanguageMeta::where([
                        'reference_id'   => $index + 1,
                        'reference_type' => MenuModel::class,
                    ])->value('lang_meta_origin');
                }

                LanguageMeta::saveMetaData($menu, $locale, $originValue);
            }
        }

        Menu::clearCacheMenuItems();
    }

    /**
     * @param int $index
     * @param array $menuNode
     * @param string $locale
     * @param int $menuId
     * @param int $parentId
     */
    protected function createMenuNode(int $index, array $menuNode, string $locale, int $menuId, int $parentId = 0): void
    {
        $menuNode['menu_id'] = $menuId;
        $menuNode['parent_id'] = $parentId;

        if (Arr::has($menuNode, 'children')) {
            $children = $menuNode['children'];
            $menuNode['has_child'] = true;

            unset($menuNode['children']);
        } else {
            $children = [];
            $menuNode['has_child'] = false;
        }

        $createdNode = MenuNode::create($menuNode);

        if ($children) {
            foreach ($children as $child) {
                $this->createMenuNode($index, $child, $locale, $menuId, $createdNode->id);
            }
        }
    }
}
