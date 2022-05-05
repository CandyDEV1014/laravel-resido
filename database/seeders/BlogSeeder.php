<?php

namespace Database\Seeders;

use Botble\ACL\Models\User;
use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Base\Supports\BaseSeeder;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\CategoryTranslation;
use Botble\Blog\Models\Post;
use Botble\Blog\Models\PostTranslation;
use Botble\Blog\Models\Tag;
use Botble\Blog\Models\TagTranslation;
use Botble\Language\Models\LanguageMeta;
use Botble\Slug\Models\Slug;
use Faker\Factory;
use Html;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use RvMedia;
use SlugHelper;

class BlogSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->uploadFiles('news');

        Post::truncate();
        Category::truncate();
        Tag::truncate();
        PostTranslation::truncate();
        CategoryTranslation::truncate();
        TagTranslation::truncate();
        Slug::where('reference_type', Post::class)->delete();
        Slug::where('reference_type', Tag::class)->delete();
        Slug::where('reference_type', Category::class)->delete();
        MetaBoxModel::where('reference_type', Post::class)->delete();
        MetaBoxModel::where('reference_type', Tag::class)->delete();
        MetaBoxModel::where('reference_type', Category::class)->delete();
        LanguageMeta::where('reference_type', Post::class)->delete();
        LanguageMeta::where('reference_type', Tag::class)->delete();
        LanguageMeta::where('reference_type', Category::class)->delete();

        $faker = Factory::create();

        $posts = [
            [
                'name' => 'Why people choose listio for own properties',
            ],
            [
                'name' => 'List of benifits and impressive listeo services',
            ],
            [
                'name' => 'What People Says About Listio Properties',
            ],
            [
                'name' => 'Why People Choose Listio For Own Properties',
            ],
            [
                'name' => 'List Of Benifits And Impressive Listeo Services',
            ],
            [
                'name' => 'What People Says About Listio Properties',
            ],
            [
                'name' => '5 of the Most Searched Outdoor Decor Trends of Summer 2021',
            ],
            [
                'name' => 'Crave a Canopy Bed? Modern Spins on This Dramatic Style',
            ],
            [
                'name' => 'The Property Brothers Reveal One Thing Never, Ever To Do to an Old House',
            ],
            [
                'name' => 'How to Build a Raised Herb Garden With Pallets',
            ],
            [
                'name' => 'Entertain in Style: 14 Products Made for an Outdoor Summer Soiree',
            ],
            [
                'name' => '6 Summer Maintenance Tasks That Could Save You Cash—Have You Done Them All?',
            ],
            [
                'name' => 'Average U.S. Rental Price Hits a Two-Year High',
            ],
            [
                'name' => 'Digital Land Rush Has People Spending Big Money on Virtual Real Estate. But Why?',
            ],
            [
                'name' => 'The Best State To Live In Right Now Is a Huge Surprise: Can You Guess?',
            ],
            [
                'name' => 'High Lumber Prices and Other Barriers Choke the Confidence of Home Builders and Home Buyers',
            ],
        ];
        $translationsPost = [
            [
                'name' => 'Giới đầu tư dè chừng với thị trường nhà đất',
            ],
            [
                'name' => 'Thời đại dịch, mua nhà hạng sang được hưởng tiện ích y tế cao cấp “trong mơ”',
            ],
            [
                'name' => 'Né bất ổn chính trị, người giàu Hồng Kông đua nhau sang London “săn” nhà',
            ],
            [
                'name' => 'Nhu cầu mua nhà đa thế hệ ở Mỹ gia tăng vì Covid',
            ],
            [
                'name' => 'Giá nhà Anh được dự báo tăng 21% trong 5 năm tới',
            ],
            [
                'name' => 'Vắc xin Covid – “Phép màu” giúp BĐS bán lẻ Hồng Kông vượt qua sóng gió?',
            ],
            [
                'name' => 'Giới siêu giàu đổ xô tìm mua đảo riêng làm nơi tránh Covid',
            ],
            [
                'name' => 'Doanh số bán bất động sản hạng sang New York phục hồi mạnh mẽ',
            ],
            [
                'name' => 'Thượng Hải ra luật chặn “chiêu” ly hôn giả để hưởng ưu đãi mua nhà',
            ],
            [
                'name' => 'Dân đầu tư tích cực đi “săn” nhà đất giá mềm ở vùng phụ cận',
            ],
            [
                'name' => 'Dự án An Phước Riverside Phan Thiết “gây sốt” thị trường BĐS',
            ],
            [
                'name' => 'Hội Môi giới BĐS Việt Nam công bố kết quả bình chọn vòng 1 giải thưởng năm 2021',
            ],
            [
                'name' => 'Sơn La sẽ có khu đô thị phía Tây Nam rộng 124ha',
            ],
            [
                'name' => 'Bà Rịa - Vũng Tàu muốn xây sân bay Gò Găng quy mô 248ha',
            ],
            [
                'name' => 'Bất động sản đảo và quy hoạch hạ tầng tạo nên sức hút cho Đông Sài Gòn',
            ],
            [
                'name' => 'Điểm nóng mới của BĐS hấp lực mạnh dòng tiền đầu tư dù đại dịch',
            ],
        ];

        $categories = [
            [
                'name' => 'Latest news',
            ],
            [
                'name' => 'House architecture',
            ],
            [
                'name' => 'House design',
            ],
            [
                'name' => 'Building materials',
            ],
        ];
        
        $translationsCategory = [
            [
                'name' => 'Tin tức mới nhất',
            ],
            [
                'name' => 'Kiến trúc nhà',
            ],
            [
                'name' => 'Thiết kế nhà',
            ],
            [
                'name' => 'Vật liệu xây dựng',
            ],
        ];

        $tags = [
            [
                'name' => 'General',
            ],
            [
                'name' => 'Design',
            ],
            [
                'name' => 'Fashion',
            ],
            [
                'name' => 'Branding',
            ],
            [
                'name' => 'Modern',
            ],
        ];
        $translationsTag = [
            [
                'name' => 'Chung',
            ],
            [
                'name' => 'Thiết kế',
            ],
            [
                'name' => 'Thời trang',
            ],
            [
                'name' => 'Thương hiệu',
            ],
            [
                'name' => 'Hiện đại',
            ],
        ];

        foreach ($tags as $index => $item) {
            $item['author_id'] = 1;
            $item['author_type'] = User::class;
            $tag = Tag::create($item);

            Slug::create([
                'reference_type' => Tag::class,
                'reference_id'   => $tag->id,
                'key'            => Str::slug($tag->name),
                'prefix'         => SlugHelper::getPrefix(Tag::class),
            ]);
        }

        foreach ($translationsTag as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['tags_id'] = $index + 1;
            TagTranslation::insert($item);
        }

        foreach ($categories as $index => $item) {
            $categoryDetail = Category::create([
                'name'      => $item['name'],
                'author_id' => 1,
            ]);

            Slug::create([
                'reference_type' => Category::class,
                'reference_id'   => $categoryDetail->id,
                'key'            => Str::slug($categoryDetail->name),
                'prefix'         => SlugHelper::getPrefix(Category::class),
            ]);
        }

        foreach ($translationsCategory as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['categories_id'] = $index + 1;
            CategoryTranslation::insert($item);
        }


        foreach ($posts as $index => $item) {
            $item['content'] =
                ($index % 3 == 0 ? Html::tag('p',
                    '[youtube-video]https://www.youtube.com/watch?v=SlPhMPnQ58k[/youtube-video]') : '') .
                Html::tag('p', $faker->realText(1000)) .
                Html::tag('p',
                    Html::image(RvMedia::getImageUrl('news/' . $faker->numberBetween(1, 5) . '.jpg'))
                        ->toHtml(), ['class' => 'text-center']) .
                Html::tag('p', $faker->realText(500)) .
                Html::tag('p',
                    Html::image(RvMedia::getImageUrl('news/' . $faker->numberBetween(6, 10) . '.jpg'))
                        ->toHtml(), ['class' => 'text-center']) .
                Html::tag('p', $faker->realText(1000)) .
                Html::tag('p',
                    Html::image(RvMedia::getImageUrl('news/' . $faker->numberBetween(11, 14) . '.jpg'))
                        ->toHtml(), ['class' => 'text-center']) .
                Html::tag('p', $faker->realText(1000));
            $item['author_id'] = 1;
            $item['author_type'] = User::class;
            $item['views'] = $faker->numberBetween(100, 2500);
            $item['is_featured'] = $index < 9;
            $item['image'] = 'news/' . ($index + 1) . '.jpg';
            $item['description'] = $faker->text();
            $item['created_at'] = $faker->dateTimeBetween('-200 days');
            $post = Post::create($item);

            $post->categories()->sync([1, 2, 3, 4]);

            $post->tags()->sync([1, 2, 3, 4, 5]);
            $inserted[] = $post;
            Slug::create([
                'reference_type' => Post::class,
                'reference_id'   => $post->id,
                'key'            => Str::slug($post->name),
                'prefix'         => SlugHelper::getPrefix(Post::class),
            ]);
        }

        foreach ($translationsPost as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['posts_id'] = $index + 1;
            $item['description'] = $inserted[$index]->description;
            $item['content'] = $inserted[$index]->content;
            PostTranslation::insert($item);
        }

    }
}
