class php70(
  $user = 'www-data',
  $group = 'www-data',
  $error_repotring = 'E_ALL & ~E_DEPRECATED & ~E_STRICT'
) {

  include apt

  apt::ppa { 'ppa:ondrej/php': }

  exec { "apt-get update":
    command => "/usr/bin/apt-get update",
    require => Apt::Ppa['ppa:ondrej/php']
  }->

  exec { "apt-get install packages":
    command => "/usr/bin/apt-get install php7.0-fpm php7.0-cli php7.0-curl php-pear -y --force-yes",
    require => Exec['apt-get update']
  } ->

  exec { "install mongodb":
    command => "/usr/bin/pecl install mongodb",
    require => Exec['apt-get update']
  }

  file { "/etc/php/7.0/fpm/pool.d/www.conf":
    path    => "/etc/php/7.0/fpm/pool.d/www.conf",
    content => template('php70/php-fpm-www.conf.erb'),
    require => Exec['apt-get install packages'],
    notify  => Service["php7.0-fpm"]
  }
  file { "/etc/php/7.0/fpm/php.ini":
    path    => "/etc/php/7.0/fpm/php.ini",
    content => template('php70/php-fpm.ini.erb'),
    require => Exec['apt-get install packages'],
    notify  => Service["php7.0-fpm"]
  }

  file { "/etc/php/7.0/fpm/php-fpm.conf":
    path    => "/etc/php/7.0/fpm/php-fpm.conf",
    content => template('php70/php-fpm.conf.erb'),
    require => Exec['apt-get install packages'],
    notify  => Service["php7.0-fpm"]
  }

  file { "mongodb_fpm":
    path    => "/etc/php/7.0/fpm/conf.d/20-mongodb.ini",
    content => "
    extension=mongodb.so
    ",
    require => Exec['install mongodb'],
    notify  => Service["php7.0-fpm"]
  }

  file { "mongodb_cli":
    path    => "/etc/php/7.0/cli/conf.d/20-mongodb.ini",
    content => "
    extension=mongodb.so
    ",
    require => Exec['install mongodb'],
    notify  => Service["php7.0-fpm"]
  }

  file { "mongodb_mods":
    path    => "/etc/php/7.0/mods-available/mongodb.ini",
    content => "
    extension=mongodb.so
    ",
    require => Exec['install mongodb'],
    notify  => Service["php7.0-fpm"]
  }

  service { 'php7.0-fpm':
    ensure  => 'running',
    enable  => true,
    require => Exec['apt-get install packages']
  }
}
