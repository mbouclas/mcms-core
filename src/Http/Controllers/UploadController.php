<?php


namespace Mcms\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Mcms\Core\Services\File\FileConfiguratorContract;
use Mcms\Core\Services\File\FileService;
use Mcms\Core\Services\Image\ImageConfiguratorContract;
use Mcms\Core\Services\Image\ImageService;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    protected $image;
    protected $file;

    public function __construct(ImageService $image, FileService $fileService)
    {
        $this->image = $image;
        $this->file = $fileService;
    }

    public function handle(Request $request, $type)
    {
        if (!$request->hasFile('file')) {
            return ['error' => 'invalid upload'];
        }

        if (!$request->file('file')->isValid()) {
            return ['error' => 'upload not successful'];
        }

        //route
        return $this->{"upload" . ucfirst($type)}($request);
    }

    protected function uploadImage(Request $request)
    {
        if ($request->has('model')) {
            $model = new $request->model();
            $configuration = new $model->imageConfigurator($request->input('item_id'));
        } else {
            $configuration = new $request->configurator($request->input('item_id'));
        }

        if (!$configuration instanceof ImageConfiguratorContract) {
            return ['error' => 'not a valid configuration passed'];
        }

        event('image.upload.started', $request);

        $image = $this->image
            ->configure($configuration)
            ->handleUpload($request);

        if ($request->has('resize')){
            if (!$request->input('resize') || $request->input('resize') == 'true') {
                $image = $image->resize();

                if ($request->has('copySettings') && $request->copySettings !== 'null' && is_string($request->input('copySettings'))){
                    $copySettings = json_decode($request->input('copySettings'), true);
                    if (! empty($copySettings) && (isset($copySettings['resize']) || (isset($copySettings['width'])))){
                        $image = $image->resizeTo($copySettings);
                    }
                }
            }
        }

        //Ignore thumbs, they are saved in the model
        if ($request->input('type') != 'thumb') {
            $model = $image->save();
            event('image.upload.done', ['image' => $model]);
            return $model;
        }

        if ($request->has('expect')) {
            $expect = $request->input('expect');
            if ($expect == 'redactor') {
                $model = $image->model();

                return [
                    'filelink' => $model['data']['url']
                ];
            }
        }

        $model = $image->model();

        event('image.upload.done', ['image' => $model]);
        return $model;
    }

    protected function uploadFile(Request $request)
    {
        if ($request->has('model')) {
            $model = new $request->model();
            $configuration = new $model->fileConfigurator($request->input('item_id'));
        } else {
            $configuration = new $request->configurator($request->input('item_id'));
        }


        if (!$configuration instanceof FileConfiguratorContract) {
            return ['error' => 'not a valid configuration passed'];
        }

        event('file.upload.started', $request);

        $file = $this->file
            ->configure($configuration)
            ->handleUpload($request);

        if ($request->has('expect')) {
            $expect = $request->input('expect');
            if ($expect == 'redactor') {
                $model = $file->model();

                return [
                    'filelink' => $model['data']['url'],
                    'filename' => $model['data']['fileName']
                ];
            }
        }



        if ($request->has('passThrough') &&  $request->passThrough){
            return $file->model();
        }

        $model = $file->save();
        event('file.upload.done', ['file' => $model]);
        return $model;
    }
}