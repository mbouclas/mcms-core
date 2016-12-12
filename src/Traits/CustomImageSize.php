<?php

namespace Mcms\Core\Traits;


use Mcms\Core\Models\Image;
use Mcms\Core\Services\Image\ImageService;
use Mcms\Core\Services\Image\Resize;

trait CustomImageSize
{
    /**
     * use saveObject only when accessing a direct property like ->thumb.
     * for anything else, just set it to false and get the result
     *
     * @example $page->resize('vertical')['url']; //return the url of a custom image size
     *
     * @param $alias
     * @param string $object
     * @param bool $saveObject
     * @return null
     */
    public function resize($alias, $object = 'thumb', $saveObject = true)
    {
        if (is_string($object)){
            $imageObject = $this->{$object};
        }

        if ( ! is_array($alias) && ! isset($this->config['images']['customCopies'][$alias])) {
            return null;
        }



        if (is_array($alias)){
            $copy = $alias;
            $alias = $copy['alias'];
            $customCopy = $copy;
        } else {
            $customCopy = $this->config['images']['customCopies'][$alias];
            $copy = $customCopy;
        }

        if (isset($imageObject['copies'][$alias])){
            return $imageObject['copies'][$alias];
        }

        if ( ! isset($imageObject['copies']['originals'])) {
            return null;
        }

        $imageService = new ImageService(new Image());
        $configuration = new $this->imageConfigurator($this->id);
        $image = $imageService
            ->configure($configuration);
        $target = $configuration->formatCopyFileName($imageObject['data']['path'],
            $customCopy);

        $image->resizer->handle($imageObject['copies']['originals']['path'], $target, $copy);
        $newCopy = [
            'path' => $target,
            'url' => $configuration->formatCopyUrl($target, $copy)
        ];

        $copies = array_merge($this->{$object}['copies'], [$alias => $newCopy]);

        $this->{$object} = array_merge($this->{$object}, ['copies'=>$copies]);

        if ( ! $saveObject){
            return $this->{$object};
        }

        $this->save();

        return $this->{$object}['copies'][$alias];
    }
}