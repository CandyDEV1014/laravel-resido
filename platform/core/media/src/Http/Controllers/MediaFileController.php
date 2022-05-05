<?php

namespace Botble\Media\Http\Controllers;

use Botble\Media\Chunks\Exceptions\UploadMissingFileException;
use Botble\Media\Chunks\Handler\DropZoneUploadHandler;
use Botble\Media\Chunks\Receiver\FileReceiver;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use RvMedia;
use Storage;
use Validator;

/**
 * @since 19/08/2015 07:50 AM
 */
class MediaFileController extends Controller
{
    /**
     * @var MediaFileInterface
     */
    protected $fileRepository;

    /**
     * @param MediaFileInterface $fileRepository
     */
    public function __construct(MediaFileInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function postUpload(Request $request)
    {
        if (!RvMedia::isChunkUploadEnabled()) {
            $result = RvMedia::handleUpload(Arr::first($request->file('file')), $request->input('folder_id', 0));

            return $this->handleUploadResponse($result);
        }

        try {
            // Create the file receiver
            $receiver = new FileReceiver('file', $request, DropZoneUploadHandler::class);
            // Check if the upload is success, throw exception or return response you need
            if ($receiver->isUploaded() === false) {
                throw new UploadMissingFileException;
            }
            // Receive the file
            $save = $receiver->receive();
            // Check if the upload has finished (in chunk mode it will send smaller files)
            if ($save->isFinished()) {
                $result = RvMedia::handleUpload($save->getFile(), $request->input('folder_id', 0));

                return $this->handleUploadResponse($result);
            }
            // We are in chunk mode, lets send the current progress
            $handler = $save->handler();

            return response()->json([
                'done'   => $handler->getPercentageDone(),
                'status' => true,
            ]);
        } catch (Exception $exception) {
            return RvMedia::responseError($exception->getMessage());
        }
    }

    /**
     * @param array $result
     * @return JsonResponse
     */
    protected function handleUploadResponse(array $result)
    {
        if ($result['error'] == false) {
            return RvMedia::responseSuccess([
                'id'  => $result['data']->id,
                'src' => RvMedia::url($result['data']->url),
            ]);
        }

        return RvMedia::responseError($result['message']);
    }

    /**
     * @param Request $request
     * @return ResponseFactory|JsonResponse|Response
     */
    public function postUploadFromEditor(Request $request)
    {
        return RvMedia::uploadFromEditor($request);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function postDownloadUrl(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'url' => 'required',
        ]);

        if ($validator->fails()) {
            return RvMedia::responseError($validator->messages()->first());
        }

        $result = RvMedia::uploadFromUrl($request->input('url'), $request->input('folderId'));

        if ($result['error'] == false) {
            return RvMedia::responseSuccess([
                'id'        => $result['data']->id,
                'src'       => Storage::url($result['data']->url),
                'url'       => $result['data']->url,
                'message'   => trans('core/media::media.javascript.message.success_header')
            ]);
        }

        return RvMedia::responseError($result['message']);
    }
}
