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
  $nginx_configuration_file = 'local'
# $daemon_user = 'deploybot'
  $error_reporting = '0'
  $newrelic_key = "1234567890123456789012345678901234567890"
  $newrelic_app_name = "test-new-relic-app-name"

  include stdlib
  include apt
  include composer

  class { 'sethostname' :
    host_name => $host_name
  }

  class { 'newrelic::server::linux':
    newrelic_license_key  => $newrelic_key,
  } ~>
  class { 'newrelic::agent::php':
    newrelic_license_key  => $newrelic_key,
    newrelic_ini_appname  => $newrelic_app_name,
    newrelic_php_conf_dir => ['/etc/php5/mods-available'],
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

  -> class { 'php56':
    user            => $daemon_user,
    group           => $daemon_user,
    error_repotring => $error_reporting
  }
  package { "openssh-server": ensure => "installed" }

  file { "/etc/sudoers.d/deploybot-user":
    content => "\
Cmnd_Alias        PUPPET = /usr/bin/puppet
Cmnd_Alias        SERVICE = /usr/bin/service
Cmnd_Alias        NPM = /usr/bin/npm
deploybot  ALL=NOPASSWD: PUPPET
deploybot  ALL=NOPASSWD: SERVICE
deploybot  ALL=NOPASSWD: NPM
Defaults env_keep += \"FACTER_server_tags\"
Defaults env_keep += \"FACTER_project_dir\"
Defaults env_keep += \"FACTER_daemon_user\"
Defaults env_keep += \"FACTER_error_reporting\"
Defaults env_keep += \"FACTER_newrelic_app_name\"
Defaults env_keep += \"FACTER_newrelic_key\"
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

  if ($ssh_port) {
    file_line { 'change_ssh_port':
      path   => '/etc/ssh/sshd_config',
      line   => "Port ${ssh_port}",
      match  => '^Port *',
      notify => Service["ssh"]
    }
  }

  class { 'nginx':
    daemon_user         => $daemon_user,
    worker_processes    => 4,
    pid                 => '/run/nginx.pid',
    worker_connections  => 1024,
    multi_accept        => 'on',
    events_use          => 'epoll',
    sendfile            => 'on',
    http_tcp_nopush     => 'on',
    http_tcp_nodelay    => 'on',
    keepalive_timeout   => '65',
    types_hash_max_size => '2048',
    server_tokens       => 'off',
    gzip                => 'off'
  }


  if $daemon_user == 'travis' {
    $port = 80
  } else {
    $port = 81
  }

  file { "gandalf_config":
    path    => "/etc/nginx/sites-enabled/gandalf.api.conf",
    content => "
    server {
    listen ${port};
    error_log /var/log/nginx.log;
    server_name gandalf.dev;
    root ${project_dir}/public;
    include ${project_dir}/config/nginx/nginx.conf;
}
    ",
    notify  => Service["nginx"]
  }
}