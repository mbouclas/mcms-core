# Preface
Laravel uses config files for every little thing, which is good 
when developing but a nightmare when you want to give your
users the power to change config settings via an interface.
This little package allows you to open the files, write in to
them and then save them back to the file system.

# API
The following methods allow you to manipulate tha config files.
Some of them are a bit unstable, so go with the examples.

There are two modes, safe and not so safe. The safe mode will
maintain the file formatting and also allows you to add php functions
in the file (like env or config) but it is not as easy to use
or as versatile as the not so safe mode.

The not so safe mode, allows you to manipulate the file as if it
where an array, which means you can do all sorts of stuff with it,
but you can say bye bye to the file formatting and also you can't
use php functions in the files as they are evaluated.

## Initializing in safe mode
For when you want your config file to be safely saved (maintain functions like env)
add the second parameter as true. If you really don't care see bellow
```
 $config = new ConfigFiles('mail', true);
```

### addChange($key, $value, $treatAsCode = false)
$value can be either string or array. If it is a string make sure
there are NO new lines, single line only.
```
//array example
$config->addChange('from', ['name' => $name, 'address'=> $email]);
//will output
    'from' => [
        'address' => 'hello@example.com',
        'name' => 'Example',
    ],
```

So all you have to do is pass an array and you're good to go.
It can be a multilevel one, it cannot contain php functions.

```
//array example
$config->addChange('driver', 'a driver');
//will output
    'driver' => 'a driver',
```

Just make sure it is a single line and all is well. If you want
to pass a function do this: 
```
$config->addChange('env', "env('APP_ENV', 'development')", true)
```

So just add a third parameter as true and it will be written as a php function

### addToArray($key, $value, $treatAsCode=false)
Append a line to an array, imagine something like the providers array
```
$config->addToArray('providers', '\FrontEnd\CustomServiceProvider::class', true)
//output
FrontEnd\CustomServiceProvider::class,
```
Or
```
$config->addToArray('from', "'name' => 'assd', 'address'=> 'asasa'", true);
//output
'from' => [
   'name' => 'assd', 'address'=> 'asasa'
],
```
Or
```
//nested array, use it with caution and only after testing it
$config->addToArray('disks', "'media' => [
                       'driver' => 'local',
                       'root' => public_path('media')
                   ]", true);
//output
'disks' => [
    'media' => [
               'driver' => 'local',
               'root' => public_path('media')
           ]
]
```
This will output your input, as a function with a , in the end

You can do the same with $treatAsCode set to false for plain stings
or evaluated function output

### contents()
returns the file contents at their current state after all the applied changes

### contents
Direct assignment to the the file contents

### save($debug=false)
Saves the file contents to disk
```
$config->save();
```

## Not so safe mode
Use it ONLY if you don't care about formatting or don't have
any php code in the config file
```
$config = new ConfigFiles('mail');
        $config->contents['from']['address'] = 'info@bob.com';
        $config->contents['from']['name'] = 'A company';
        $config->save();
```