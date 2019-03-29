# Tomos Package
Simple Backend for Frontend.

## Installing Brio
1. Unzip package into a packages directory within your application's root directory.  
2. Add dependency in `composer.json` file:
```json
"require": {
	"php": ">=7.0.0",
	"mako/framework": "5.7.*",
	"packages/tomos": "*"
}
```
And add local repository path:
```json
"repositories": [
    {
        "type": "path",
        "url": "packages/tomos",
        "options": {
            "symlink": true
        }
    }
]
```
3. Rur `composer install` or `composer update` command in console terminal.