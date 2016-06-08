class php56(
  $user = 'www-data',
  $group = 'www-data',
  $error_repotring = 'E_ALL & ~E_DEPRECATED & ~E_STRICT'
) {
  $enhancers = [
    'php5-fpm',
    'php5-cli',
    'php5-curl',
    'php5-mongo',
  ]

  include apt

  apt::ppa { 'ppa:ondrej/php5-5.6': }

  exec { "apt-get update php56":
    command => "/usr/bin/apt-get update",
    require => Apt::Ppa['ppa:ondrej/php5-5.6']
  }->

  exec { "apt-get install packages":
    command => "/usr/bin/apt-get install php5-fpm php5-cli php5-curl php5-mongo -y --force-yes",
    require => Exec['apt-get update php56']
  }

#  package { $enhancers: ensure  => 'installed',
#    install_options => ['-y --force-yes', '--allow-unauthenticated'],require  => Exec['apt-get update php56'], }

  file { "/etc/php5/fpm/pool.d/www.conf":
    path    => "/etc/php5/fpm/pool.d/www.conf",
    content => template('php56/php-fpm-www.conf.erb'),
    require => Exec['apt-get install packages'],
    notify  => Service["php5-fpm"]
  }
  file { "/etc/php5/fpm/php.ini":
    path    => "/etc/php5/fpm/php.ini",
    content => template('php56/php-fpm.ini.erb'),
    require => Exec['apt-get install packages'],
    notify  => Service["php5-fpm"]
  }

  file { "/etc/php5/fpm/php-fpm.conf":
    path    => "/etc/php5/fpm/php-fpm.conf",
    content => template('php56/php-fpm.conf.erb'),
    require => Exec['apt-get install packages'],
    notify  => Service["php5-fpm"]
  }

  service { 'php5-fpm':
    ensure  => 'running',
    enable  => true,
    require => Exec['apt-get install packages']
  }
}
