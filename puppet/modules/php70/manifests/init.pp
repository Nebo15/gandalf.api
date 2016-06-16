class php70(
  $user = 'www-data',
  $group = 'www-data',
  $error_repotring = 'E_ALL & ~E_DEPRECATED & ~E_STRICT'
) {

  include apt

  apt::ppa { 'ppa:ondrej/php': }

  exec { "apt-get update php7.0":
    command => "/usr/bin/apt-get update php7.0",
    require => Apt::Ppa['ppa:ondrej/php']
  }->

  exec { "apt-get install packages":
    command => "/usr/bin/apt-get install php7.0-fpm php7.0-cli php7.0-curl -y --force-yes",
    require => Exec['apt-get update php7.0']
  } ->

  exec { "install mongodb":
    command => "/usr/bin/pecl install mongodb",
    require => Exec['apt-get update php7.0']
  } ->

  exec { "add mongodb.so":
    command => "echo \"extension=mongodb.so\" > /etc/php/7.0/fpm/conf.d/20-mongodb.ini && echo \"extension=mongodb.so\" > /etc/php/7.0/cli/conf.d/20-mongodb.ini && echo \"extension=mongodb.so\" > /etc/php/7.0/mods-available/mongodb.ini",
    require => Exec['apt-get update php7.0']
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

  service { 'php7.0-fpm':
    ensure  => 'running',
    enable  => true,
    require => Exec['apt-get install packages']
  }
}
