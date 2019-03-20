<?php

namespace Mcms\Core\Services\Image;


use Auth;
use Event;
use Mcms\Core\Exceptions\InvalidImageFormatException;
use Mcms\Core\Models\Image;
use Illuminate\Http\Request;
use File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * Class ImageService
 * @package Mcms\Core\Services\Image
 */
class ImageService
{
    /**
     * @var Resize
     */
    public $resizer;
    /**
     * @var Image
     */
    protected $image;
    /**
     * @var ImageValidator
     */
    protected $validator;
    /**
     * @var
     */
    protected $config;
    /**
     * @var
     */
    protected $configurator;
    /**
     * @var
     */
    protected $uploadedFile;
    /**
     * @var
     */
    protected $request;
    /**
     * @var
     */
    protected $uploadedFileInstance;
    protected $copies;

    /**
     * ImageService constructor.
     * @param Image $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
        $this->validator = new ImageValidator();
        $this->resizer = new Resize();
    }

    /**
     * @param ImageConfiguratorContract $config
     * @return $this
     */
    public function configure(ImageConfiguratorContract $config)
    {
        $this->configurator = $config;
        $this->config = $this->configurator->configure();

        return $this;
    }

    /**
     * @param $id
     * @param array $model
     * @return mixed
     */
    public function update($id, array $model)
    {
        $image = $this->image->find($id);
        $image->update($model);

        return $image;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $image = $this->image->find($id);
        //get an instance of the parent model
        $model = new $image->model;
        $config = new $model->imageConfigurator($image->item_id);
        $filesDir =  call_user_func($config->savePath);

        //delete the files
        foreach ($image->copies as $copy) {
            if (!isset($copy['url'])){
                continue;
            }

            File::delete($filesDir . $copy['url']);
        }
        //delete the record
        return $image->delete();
    }

    /**
     * @param Request $request
     * @param string $key
     * @return $this
     */
    public function handleUpload(Request $request, $key = 'file')
    {
        $this->request = $request;
        $this->uploadedFileInstance = $request->file($key);

        $this->move();

        return $this;
    }

    /**
     * Move the uploaded file somewhere
     *
     * @param null $to
     * @return $this
     */
    public function move($to = null)
    {
        if ( ! $to){
            $to = $this->configurator->uploadPath();
        }

        $filename = ($this->configurator->formatFileName($this->uploadedFileInstance->getClientOriginalName())) ?: $this->uploadedFileInstance->getClientOriginalName();

        $this->uploadedFile = $this->uploadedFileInstance->move($to, $filename);


        return $this;
    }

    public function handleFromPath(UploadedFile $file, $request)
    {
        $this->request = $request;
        $path = storage_path();
        $file_name = $file->getFilename();
        $tmp = "{$path}/{$file_name}";
        File::copy($file->getPathname(), $tmp);

        $this->uploadedFileInstance = new UploadedFile($tmp, $file_name, $file->getMimeType(), null, true);

        $this->move();

        return $this;
    }

    /**
     * Resize an image creating image copies
     *
     * @return $this
     */
    public function resize()
    {
        $copies = [];

        foreach ($this->config['copies'] as $key=>$copy) {
            $target = $this->configurator->formatCopyFileName($this->uploadedFile, $copy);
            $this->resizer->handle($this->uploadedFile, $target, $copy);

            $copies[$key] = [
                'url' => $this->configurator->formatCopyUrl($target, $copy),
                'path' => $target
            ];
        }

        $original = $this->keepOriginal();

        if ($original){
            $copies['originals'] = [
                'url' => $this->configurator->formatCopyUrl($original, []),
                'path' => $original
            ];
        }

        $this->copies = $copies;
        return $this;
    }

    /**
     * Keep or delete the original file
     *
     * @return bool
     */
    function keepOriginal(){

        if (isset($this->config['keepOriginals']) && $this->config['keepOriginals']){
            $originalsDir = $this->configurator->formatOriginalsFolder($this->uploadedFile);

            if ( ! File::exists($originalsDir)){
                File::makeDirectory($originalsDir);
            }

            $originalFile = $originalsDir . basename($this->uploadedFile);
            File::move($this->uploadedFile, $originalFile);
            return $originalFile;
        }

        $this->cleanUp();

        return false;
    }

    /**
     * Delete uploaded file
     */
    public function cleanUp()
    {
        File::delete($this->uploadedFile);
    }

    public function resizeTo(array $copy)
    {
        $target = $this->configurator->formatCopyFileName($this->uploadedFile, $copy);
        $this->resizer->handle($this->uploadedFile, $target, $copy);
        $file = new \Symfony\Component\HttpFoundation\File\File($target);
        $this->uploadedFile = new UploadedFile($target, basename($target), $file->getSize(), $file->getMimeType(), null, true);
        return $this;
    }

    public function copy($to = null){
        if ( ! $to){
            $to = $this->configurator->uploadPath();
        }

        $filename = ($this->configurator->formatFileName($this->uploadedFileInstance->getClientOriginalName())) ?: $this->uploadedFileInstance->getClientOriginalName();

        $this->uploadedFile = $this->uploadedFileInstance->copy($to, $filename);
    }

    /**
     * @return mixed
     */
    public function uploaded_file()
    {
        return $this->uploadedFile;
    }

    /**
     * Store the processed image
     */
    public function save()
    {
        return $this->store($this->model());
    }

    /**
     * @param array $model
     * @return string|static
     */
    public function store(array $model)
    {
        try {
            $this->validator->validate($model);
        }
        catch (InvalidImageFormatException $e){
            return $e->getMessage();
        }

        return $this->image->create($model);
    }

    public function model()
    {
        $request = (is_array($this->request)) ? $this->request : $this->request->toArray();
        unset($request['file']);

        $model = array_merge(
            $request,
            ['data' => [
                'path' => $this->uploadedFile->getPathname(),
                'url' => $this->configurator->formatCopyUrl($this->uploadedFile->getPathname(), [])
            ]],
            ['model' => get_class($this->configurator->model)],
            ['user_id' => Auth::user()->id],
            ['copies'=> $this->copies]
        );

        if ($this->config['optimize']){
            event('image.uploaded', ['image' => $model]);
        }

        // override the model output. Good for when using a CDN and want to change the urls
        if (isset($this->config['afterUpload'])) {
            return (new $this->config['afterUpload'])->handle($model);
        }

        return $model;
    }

    public function updateSortOrder(array $images)
    {
        foreach ($images as $index => $image){
            $this->image
                ->where('id', $image['id'])
                ->update(['orderBy' => $index]);
        }

        return $this;
    }

}