class php56 {
  $enhancers = [
    'php5-fpm',
    'php5-cli',
    'php5-curl',
  ]
  include apt
  apt::ppa { 'ppa:ondrej/php5-5.6': }
  package { $enhancers:
    ensure  => 'installed',
    install_options => ['-y', '--force-yes'],
    require => Apt::Ppa['ppa:ondrej/php5-5.6']
  }
  file { "/etc/php5/fpm/pool.d/www.conf":
    path => "/etc/php5/fpm/pool.d/www.conf",
    content => template('php56/php-fpm-www.conf.erb'),
    require => Package[$enhancers]
  }
  file { "/etc/php5/fpm/php.ini":
    path => "/etc/php5/fpm/php.ini",
    content => template('php56/php-fpm.ini.erb'),
    require => Package[$enhancers]
  }

  file { "/etc/php5/fpm/php-fpm.conf":
    path => "/etc/php5/fpm/php-fpm.conf",
    content => template('php56/php-fpm.conf.erb'),
    require => Package[$enhancers]
  }
}
