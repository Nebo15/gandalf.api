# Puppet Module for PHP composer

[![Build Status](https://api.travis-ci.org/Brainsware/puppet-composer.png?branch=master)](https://travis-ci.org/Brainsware/puppet-composer)

manage installation of composer as well as the installation and update of
projects with composer.

This project was initially forked from
[willdurand-composer](https://github.com/willdurand/puppet-composer), adding
basic functionality already provided from
[tPl0ch-composer](https://github.com/tPl0ch/puppet-composer). Because everyone
is a unique snowflake and *we* needed one true (i.e.: our) way of handling
composer.


## Documentation

Installing composer

```puppet
     include composer
```

Installing composer from a package:

```puppet
     class { 'composer':
       provider => 'package',
     }
```

Installing composer from a that installs it in a weird directory:

```puppet
     class { 'composer':
       provider   => 'package',
       target_dir => '/opt/es/bin',
     }
```

Installing composer from a third-party module's class:

```puppet
     class { 'composer':
       provider   => 'php::composer',
     }
```

Installing a project's dependencies with composer. n.b.: This directory must
already exist. We recommend tracking it with
[puppetlabs-vcsrepo](http://forge.puppetlabs.com/puppetlabs/vcsrepo)

```puppet
     # track yolo-site's stable branch:
     vcsrepo { '/srv/web/yolo':
       ensure   => 'latest'
       provider => 'git',
       source   => 'git://example.com/yolo-site.git',
       revision => 'stable',
     }

     # install yolo project without dev packages:
     composer::project { 'yolo':
       ensure  => 'installed',
       target  => '/srv/web/yolo',
       dev     => false,
       require => Vcsrepo['/srv/web/yolo'],
     }
```

To keep a project up-to-date we can use the `ensure => latest`

```puppet
     # Keep yolo project up-to-date, with dev packages, ignore the lock file:
     composer::project { 'yolo':
       ensure  => 'latest',
       target  => '/srv/web/yolo',
       dev     => true,
       lock    => true,
       require => Vcsrepo['/srv/web/yolo'],
     }
```

To create a project based on another package we can use the `$source`

```puppet
    composer::project { 'typo3-cms':
      ensure => 'present',
      source => 'typo3/cms-base-distribution:~6.2',
      target => '/srv/web/typo3',
    }
```

## Patches and Testing

Contributions are highly welcomed, more so are those which contribute patches
with tests. Or just more tests! We have
[rspec-puppet](http://rspec-puppet.com/) and
[beaker](https://github.com/puppetlabs/beaker) tests. When [contributing
patches](Github WorkFlow), please make sure that your patches pass tests. For
more info, please check our [CONTRIBUTING](./CONTRIBUTING.md)


## Release process

The module is versioned according to [semver](http://semver.org/). It uses
[blacksmith](https://github.com/maestrodev/puppet-blacksmith) for cutting
releases.


License
-------

Apache Software License 2.0. See the [LICENSE](./LICENSE) for the full text, or
checkout the [FAQ](https://www.apache.org/foundation/license-faq.html) for what
that actually means.


Contact
-------

You can send us questions via mail
[puppet@brainsware.org](puppet@brainsware.org), or reach us IRC:
[igalic](https://github.com/igalic) hangs out in
[#puppet](irc://freenode.org/#puppet)

Support
-------

Please log tickets and issues at our [Project's issue
tracker](https://github.com/Brainsware/puppet-composer/issues)
