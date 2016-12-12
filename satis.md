# Install satis
``` create-project composer/satis --stability=dev --keep-vcs ```

satis.json
```
{
    "name": "My Repository",
    "homepage": "http://satis.example.org",
    "repositories": [
        { 
            "type": "vcs", "url": "git@bitbucket.org:ideaseven/package-core.git",
             "options": {
                 "ssh2": {
                     "username": "composer",
                     "pubkey_file": "/home/composer/.ssh/id_rsa.pub",
                     "privkey_file": "/home/composer/.ssh/id_rsa"
                 }
             }
         }
    ],
"require" : {
"idea-seven/package-core" : "*"
},
    "archive": {
        "directory": "dist",
        "format": "tar",
        "prefix-url": "https://amazing.cdn.example.org",
        "skip-dev": true
    },
    "require-all": true,
 "require-dependencies": true
}
```

* Name packages as idea-seven/package-name . No caps

if you have an issue with ssh
* eval "$(ssh-agent)"
* ssh-add

``` php bin/satis build <configuration file> <build-dir> ```

This should provide with a satis server. Setup security afterwards


# Composer
Add the following to your composer

```
    "repositories": [
        {
            "type": "composer",
            "url": "http://satis.example.org"
        }
    ]
```