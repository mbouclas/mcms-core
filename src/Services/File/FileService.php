<?php

namespace Mcms\Core\Services\File;


use \Event;
use Auth;
use File;
use Mcms\Core\Models\FileGallery;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    public $model;

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

    /**
     * FileService constructor.
     */
    public function __construct()
    {
        $this->model = new FileGallery();
    }

    public function configure(FileConfiguratorContract $config)
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
        $file = $this->model->find($id);
        $file->update($model);

        return $file;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $file = $this->model->find($id);
        //get an instance of the parent model
        $model = new $file->model;
        $config = new $model->fileConfigurator($file->item_id);
        $filesDir =  call_user_func($config->savePath);

        File::delete($filesDir . $file->url);
        //delete the record
        return $file->delete();
    }

    public function save()
    {
        $model = $this->model();
        $file = [
            'url' => $model['data']['url'],
            'file_name' => $model['data']['fileName'],
            'info' => $model['data'],
            'item_id' => $model['item_id'],
            'user_id'=> $model['user_id'],
            'model' => $model['model'],
            'active' => false,
        ];

        return $this->store($file);
    }

    public function model()
    {
        $request = ( ! is_array($this->request)) ? $this->request->toArray() : $this->request;
        unset($request['file']);

        $model = array_merge(
            $request,
            ['data' => [
                'path' => $this->uploadedFile->getPathname(),
                'url' => $this->configurator->formatUrl($this->uploadedFile->getPathname(), []),
                'fileName' => $this->uploadedFile->getFilename()
            ]],
            ['model' => get_class($this->configurator->model)],
            ['user_id' => Auth::user()->id],
            ['item_id' => isset($request['item_id']) ? $request['item_id'] : null]
        );

        event('file.uploaded', ['file' => $model]);

        return $model;
    }

    /**
     * @param array $model
     * @return static
     */
    public function store(array $model)
    {
        return $this->model->create($model);
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

        $this->uploadedFileInstance = new UploadedFile($tmp, $file_name, $file->getSize(), $file->getMimeType(), null, true);

        $this->move();

        return $this;
    }

    public function updateSortOrder(array $files)
    {

        foreach ($files as $index => $file){
            $this->model
                ->where('id', $file['id'])
                ->update(['orderBy' => $index]);
        }

        return $this;
    }
}