# Preface
The settings manager allows you to bind user defined fields to either entities (like extra fields)
or to models (like user). It creates an array of all available components based on a template
configuration found in `admin.php` config file. Editing the components array in this file allows
you to create new custom components which you can then use in your application.

## API
There is a service powering the entire thing, `Mcms\Core\SettingsManager\SettingsManager`.
The service exposes the database model for direct operations to the DB in case the available
methods do not cover your use case.
```
$settingsManager = new SettingsManager();
$settingsManager->model->find(1);
```

The basic lookup should always be via the slug, as it makes it so much easier to find entities. 
Using the static caller you can find something in one line. For example, we can get the User profile
fields simply by doing 
```
SettingsManager::find('user-profiles');
```

which equals to 
```
$settingsManager->model->where('slug', $slug)->first();
```

## The angular part
Attach extra fields to your model using the Settings creator component like so
```
<settings-creator ng-model="VM.ProfileSettings"></settings-creator>
```

where `VM.ProfileSettings` needs to be an array. You can then render the fields using the 
render-settings component like so

```
<render-settings items="VM.ProfileSettings" ng-model="VM.User.profile"></render-settings>
```
where `VM.User.profile` is an object

## The frontend part
After doing all that, you are left with a nice array ub the frontend which you can use in
your blade templates like so
```
{{ $User->profile['website-url'] }}
```

