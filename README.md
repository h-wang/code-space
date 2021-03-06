CodeSpace
======

Manages your local development projects and code repositories.

When developing projects, especially micro-services, we often end up with hundres of code repositories on our dev machines. CodeSpace helps to manage these repositories and do batch operations on them easily.
You can use it as a stand-alone tool. There is no need to integrate into any project.

CodeSpace is inspired by the [LinkORB Projex](https://github.com/linkorb/projex) and [Hotel](https://github.com/typicode/hotel).
## Features

### Project scanner

Recursively scans the giving directory to find projects/repositories.
Scan the code repositories and show output to the console `bin/codespace scan [--path=~/git]`

### Export projects
1. Export to HTML `bin/codespace export:html /path/to/the/target.html [--path=~/git]`
2. Export to CSV `bin/codespace export:csv /path/to/the/target.csv [--path=~/git]`

### Auto-Generate web-server config files
With the export feature, we can easily generate Nginx server configuration files for multiple projects.
It automatically detects your OS and figure out where to put the configuration files and log files; automatically detects the type of project and point to the correct __web__ directory.
```
bin/codespace export:nginx-conf [--path=~/git] [--apply]
```
__*__ At the moment, for OS support, only MacOS with Homebrew and Linux are supported. Only Symfony 3 and 4 projects are supported. Contributions needed.

### Do git fetch on all projects
When the `--pull` option is used, the `git pull` command is executed instead of the `git fetch` command.
```
bin/codespace git fetch [--path=~/git] [--pull]
```

### Auto-update your favorite IDE's project manager
Scan the repositories and make them available to your IDE's project manager plugins. Now __Atom__ and __VSCode__ are supported.
The project managers are:

    https://atom.io/packages/project-manager
    https://marketplace.visualstudio.com/items?itemName=alefragnani.project-manager

Commands:
Without specifying the `--ide=` option, both __Atom__ and __VSCode__ are updated.
```
bin/codespace ide:pm [--ide=atom] [--path=~/git]
```

## Installation:
Use the source code:
```
composer install
```
Use the phar:
```
php code-space.phar
```

## Use
Base command:
```
`bin/codespace`
# or
php code-space.phar
```
You can view all available commands by running the base command.


## License

Please refer to the included LICENSE.md file
