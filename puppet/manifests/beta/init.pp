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

  $host_name = "beta.gndf.io"

  include stdlib
  include apt
  include composer

  class { 'sethostname' :
    host_name => $host_name
  }

  package { 'install uuid-runtime':
    name    => 'uuid-runtime',
    ensure  => installed,
  }
  class { 'nebo15_users': } ->
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

  package { "openssh-server": ensure => "installed" }

  file { ["/etc/sudoers.d/deploybot"]:
    ensure => "directory",
    owner  => root,
    group  => root,
    mode   => 0440
  }->
  file { "/etc/sudoers.d/deploybot/first":
    content => "\
Cmnd_Alias        API_PUPPET = /usr/bin/puppet
Cmnd_Alias        API_SERVICE = /usr/bin/service
deploybot  ALL=NOPASSWD: API_PUPPET
deploybot  ALL=NOPASSWD: API_SERVICE
",
    mode    => 0440,
    owner   => root,
    group   => root,
  } ->
  file { "/etc/sudoers.d/deploybot-user":
    content => "\
#includedir /etc/sudoers.d/deploybot
",
    mode    => 0440,
    owner   => root,
    group   => root,
  }

  service { "ssh":
    ensure  => "running",
    enable  => "true",
    require => Package["openssh-server"]
  }

  file_line { 'change_ssh_port':
    path   => '/etc/ssh/sshd_config',
    line   => "Port 2020",
    match  => '^Port *',
    notify => Service["ssh"]
  }
}