class sethostname(
  $host_name = undef
) {
  file { "/etc/hostname":
    ensure  => present,
    owner   => root,
    group   => root,
    mode    => 644,
    content => "$host_name\n",
    notify  => Exec["set-hostname"],
  }
  exec { "set-hostname":
    command => "/bin/hostname -F /etc/hostname",
    unless  => "/usr/bin/test `hostname` = `/bin/cat /etc/hostname`",
  }
}

node default {

  $host_name = "gandalf.dev"

  include stdlib
  include apt
  include composer

  class { 'sethostname' :
    host_name => "gandalf.dev"
  }

  package { 'install uuid-runtime':
    name    => 'uuid-runtime',
    ensure  => installed,
  }
  class { 'timezone':
    timezone => 'UTC',
  } ->
  class { 'locales':
    default_locale  => 'en_US.UTF-8',
    locales         => ['en_US.UTF-8 UTF-8'],
  }->
    /**
     Mongo part start
    */
  apt::source { 'mongo_3.2':
    location => 'http://repo.mongodb.org/apt/ubuntu/ trusty/mongodb-org/3.2',
    key      => 'EA312927',
    release  => 'multiverse', repos => "multiverse", include => { 'deb' => true, }
  }->
  exec { "apt-get update":
    command => "/usr/bin/apt-get update",
    require => Apt::Source['mongo_3.2']
  }->
  package { 'mongodb_client':
    ensure          => present,
    name            => 'mongodb-org=3.2.0',
    tag             => 'mongodb',
    install_options => ['-y', '--force-yes'],
    require         => Exec['apt-get update'],
  }

  /**
   Mongo part end
  */
}