<?php

namespace Botble\RealEstate\Http\Controllers\Fronts;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Repositories\Interfaces\ReviewInterface;
use Botble\RealEstate\Repositories\Interfaces\AccountActivityLogInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\RealEstate\Http\Requests\ReviewRequest;
use Botble\RealEstate\Http\Requests\PostReviewRequest;
use Botble\RealEstate\Models\ReviewMeta;

class PublicReviewController
{

    /**
     * @var ReviewInterface
     */
    protected $reviewRepository;
   
    /**
     * @var AccountActivityLogInterface
     */
    protected $activityLogRepository;

    /**
     * PublicReviewController constructor.
     * @param ReviewInterface $reviewRepository
     * @param AccountActivityLogInterface $accountActivityLogRepository
     */
    public function __construct(
        ReviewInterface $reviewRepository,
        AccountActivityLogInterface $accountActivityLogRepository
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->activityLogRepository = $accountActivityLogRepository;
    }


    /**
     * @param ReviewRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postCreateReview(ReviewRequest $request, BaseHttpResponse $response)
    {
        $exists = $this->reviewRepository->count([
            'account_id' => auth('account')->id(),
            'reviewable_id'  => $request->input('reviewable_id'),
            'reviewable_type'  => $request->input('reviewable_type'),
        ]);

        $setting = \Botble\Setting\Models\Setting::where('key', 'real_estate_review_fields')->first();
        $settingReviewFields = json_decode($setting->value);

        $getReviewMeta = \Botble\RealEstate\Models\ReviewMeta::where('review_id', $request->input('review_id'))->get();

        if ($request->input('edit') == 'yes') {
            foreach ($getReviewMeta as $key => $value) {
                $updateReview = \Botble\RealEstate\Models\ReviewMeta::find($value->id);

                foreach ($settingReviewFields as $k => $v) {
                    if ($v[0]->value == $updateReview->key) {
                        $updateReview->value = $request->input('meta')[$updateReview->key];
                        $updateReview->save();       
                    }
                }
            }   

            $getMyReview            = \Botble\RealEstate\Models\Review::find($request->input('review_id'));
            $getMyReview->star      = $request->input('star');
            $getMyReview->comment   = $request->input('comment');
            $getMyReview->save();

            // $this->activityLogRepository->createOrUpdate(['action' => 'update_review']);
            $property = app(PropertyInterface::class)->findOrFail($request->input('reviewable_id'));
            
            $this->activityLogRepository->createOrUpdate([
                'action'         => 'update_my_review',
                'reference_id'   => $request->input('reviewable_id'),
                'reference_type' => $request->input('reviewable_type'),
                'reference_name' => $property->name,
                'reference_url'  => $property->url,
            ]);

            if (auth('account')->id() != $property->author_id) {
                $this->activityLogRepository->createOrUpdate([
                    'action'         => 'update_client_review',
                    'reference_id'   => $request->input('reviewable_id'),
                    'reference_type' => $request->input('reviewable_type'),
                    'reference_name' => $property->name,
                    'reference_url'  => $property->url,
                ]);
            }
            
        }else{
            if (!$exists) {
                $request->merge(['account_id' => auth('account')->id()]);

                $review = $this->reviewRepository->createOrUpdate($request->input());
                
                foreach ($request->input('meta') as $key => $value) {
                    ReviewMeta::setMeta($key, $value, $review->id);
                }

                $property = app(PropertyInterface::class)->findOrFail($request->input('reviewable_id'));
                
                $this->activityLogRepository->createOrUpdate([
                    'action'         => 'add_my_review',
                    'reference_id'   => $request->input('reviewable_id'),
                    'reference_type' => $request->input('reviewable_type'),
                    'reference_name' => $property->name,
                    'reference_url'  => $property->url,
                ]);

                if (auth('account')->id() != $property->author_id) {
                    $this->activityLogRepository->createOrUpdate([
                        'action'         => 'add_client_review',
                        'reference_id'   => $request->input('reviewable_id'),
                        'reference_type' => $request->input('reviewable_type'),
                        'reference_name' => $property->name,
                        'reference_url'  => $property->url,
                    ]);
                }
            }
            
            
            // $this->activityLogRepository->createOrUpdate(['action' => 'add_review']);
        }

        if ($request->input('edit') == 'yes') {
            return $response->setNextUrl(route('public.account.myReview'))->setMessage(__('Edited review successfully!'));
        }else{
            return $response->setMessage(__('Added review successfully!'));
        }

    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function getDeleteReview($id, BaseHttpResponse $response)
    {
        $review = $this->reviewRepository->findOrFail($id);

        if (auth()->check() || (auth('account')->check() && auth('account')->id() == $review->account_id)) {
            $review->meta()->delete();
            $this->reviewRepository->delete($review);

            $this->activityLogRepository->deleteBy(
                [
                    'account_id' => auth('account')->id(), 
                    'reference_type' => $review['reviewable_type'], 
                    'reference_id' => $review['reviewable_id']
                ]
            );

            return $response->setMessage(__('Deleted review successfully!'));
        }

        abort(401);
    }

    /**
     * @param ReviewRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function CreatePostReview(PostReviewRequest $request, BaseHttpResponse $response)
    {
        $exists = $this->reviewRepository->count([
            'account_id' => auth('account')->id(),
            'reviewable_id'  => $request->input('reviewable_id'),
            'reviewable_type'  => $request->input('reviewable_type'),
        ]);
        $setting = \Botble\Setting\Models\Setting::where('key', 'real_estate_review_fields')->first();
        $settingReviewFields = json_decode($setting->value);

        $getReviewMeta = \Botble\RealEstate\Models\ReviewMeta::where('review_id', $request->input('review_id'))->get();

        if ($request->input('edit') == 'yes') {
            foreach ($getReviewMeta as $key => $value) {
                $updateReview = \Botble\RealEstate\Models\ReviewMeta::find($value->id);

                foreach ($settingReviewFields as $k => $v) {
                    if ($v[0]->value == $updateReview->key) {
                        $updateReview->value = $request->input('meta')[$updateReview->key];
                        $updateReview->save();       
                    }
                }
            }   

            $getMyReview            = \Botble\RealEstate\Models\Review::find($request->input('review_id'));
            $getMyReview->star      = 0;
            $getMyReview->comment   = $request->input('comment');
            $getMyReview->save();

            $post = app(PostInterface::class)->findOrFail($request->input('reviewable_id'));
            
            $this->activityLogRepository->createOrUpdate([
                'action'         => 'update_my_review',
                'reference_id'   => $request->input('reviewable_id'),
                'reference_type' => $request->input('reviewable_type'),
                'reference_name' => $post->name,
                'reference_url'  => $post->url,
            ]);
            
        }else{
            if (!$exists) {
                $request->merge(['account_id' => auth('account')->id()]);

                $review = $this->reviewRepository->createOrUpdate($request->input());
                
                // foreach ($request->input('meta') as $key => $value) {
                //     ReviewMeta::setMeta($key, $value, $review->id);
                // }
                
                $post = app(PostInterface::class)->findOrFail($request->input('reviewable_id'));
                $this->activityLogRepository->createOrUpdate([
                    'action'         => 'add_my_review',
                    'reference_id'   => $request->input('reviewable_id'),
                    'reference_type' => $request->input('reviewable_type'),
                    'reference_name' => $post->name,
                    'reference_url'  => $post->url,
                ]);
            }
        }

        if ($request->input('edit') == 'yes') {
            return $response->setNextUrl(route('public.account.myReview'))->setMessage(__('Edited review successfully!'));
        }else{
            return $response->setMessage(__('Added review successfully!'));
        }

    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function getDeletePostReview($id, BaseHttpResponse $response)
    {
        $review = $this->reviewRepository->findOrFail($id);

        if (auth()->check() || (auth('account')->check() && auth('account')->id() == $review->account_id)) {

            $review->meta()->delete();
            $this->reviewRepository->delete($review);

            $this->activityLogRepository->deleteBy(
                [
                    'account_id' => auth('account')->id(), 
                    'reference_type' => $review['reviewable_type'], 
                    'reference_id' => $review['reviewable_id']
                ]
            );

            return $response->setMessage(__('Deleted review successfully!'));
        }

        abort(401);
    }
}
