# AppFlower Application Security
A few guidelines on security for deploying an appFlower application. The goal is to have AppFlower (including Symfony and AppFlower Studio) running smoothly in a secured environment.

Security Aims:

 * Run appFlower with least privileges
 * Run appFlower with as strict permissions as possible
 
## Permissions 
We want to have the application itself, and all files created under the lifecycle of a project with least required permissions. That minimum permissions is 700 in most cases. This is not so straightforward because symfony itself has few places where higher than 700 permissions are set on some files.

To change the permissions you need to

1. Create config/permissions.yml with following content:

    folders:
      root:
        path: '/'
        mode: 'u+rwx,go-rwx'
        recursive: true

2. Change your application configuration file in apps/frontend/config/frontendConfiguration.class.php and

2.1: add the require statement at the beginning of the file:

    require_once dirname(dirname(dirname(dirname(__FILE__)))).'/plugins/appFlowerPlugin/lib/afConfigCache.class.php';

2.2: Add getConfigCache() method

    public function getConfigCache()
    {
        if (null === $this->configCache) {
            $this->configCache = new afConfigCache($this);
        }

        return $this->configCache;
    }

3. Configure AF engine and studio plugins by adding following to app.yml

    all:
      afs:
        chmod_enabled: false
      appFlower:
        chmod_enabled: false

## Don't
 * Clearing Cache: If you are clearing the cache of given project and you are working as other system user than Apache server will be using - DO NOT RUN 'symfony cc' task! That task creates one file inside cache folder and it will have wrong permissions. Instead you can just clear cache/ directory and then on first request mentioned file will be created.
