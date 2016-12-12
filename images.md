# Basic stuff
check http://image.intervention.io/api/fit for details on the api

## Resize API for models. resize($alias, $object = 'thumb', $saveObject = true)
A model MUST have attached to it the following or this will fail
* A config instance
* An instance of imageConfigurator
* customCopies array in the config (optional)

Attach it to your model like so
`use CustomImageSize;`

The API is pretty simple, `$model->resize($alias)` and you're done.
 Where `$alias` we mean either a key from the customCopies array or
 an array which is essentially the copy you want to apply
 
 
#### Basic copy sample
```
$customImageSize = [
    'alias' => 'horizontal', //the copy key
    'width' => 150,
    'height' => 70,
    'quality' => 100,
    'prefix' => 'horizontal_',
    'resizeType' => 'fit', //check Intervention for resize types
    'dir' => 'thumbs/',
];
```

The `$object` parameter needs to either be a string that points
to a direct property of your model (like thumb) or an array.
When you pass an array, you get back the new copy (path, url)


The `$saveObject` parameter ONLY works on string type `$object`


#### How it works
It will use the imageConfigurator to create path and url for the new
copy. It will then create the copy on the disk. Next time you access it
you will get back whatever was saved in the model the first time it run.

Finally, if you want to save the model, it will add the new copy to the 
copies array. We are using the original file to resize the copy, so if for
any reason it is missing, you get null as a response.