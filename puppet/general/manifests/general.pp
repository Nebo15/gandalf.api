node default {
  include stdlib
  include apt


  class { 'php56':
  user            => $daemon_user,
  group           => $daemon_user,
  error_repotring => $error_reporting
  } ->

  class { 'newrelic::server::linux':
    newrelic_license_key  => $newrelic_key,
  } ~>
  class { 'newrelic::agent::php':
    newrelic_license_key  => $newrelic_key,
    newrelic_ini_appname  => $newrelic_app_name,
    newrelic_php_conf_dir => ['/etc/php5/mods-available'],
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
    add_header 'Access-Control-Allow-Origin' *;
    add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS, PUT, DELETE';
    add_header 'Access-Control-Allow-Headers' 'Authorization,Content-Type,Accept,Origin,User-Agent,DNT,Cache-Control,Keep-Alive,If-Modified-Since,X-Project-ID,X-Date,X-Accept-Charset,X-Application-ID,X-Device-Information,X-Application-Secret-Hash,X-Device-Push-Token,X-Application';
    add_header 'X-Frame-Options' 'DENY';
    if (\$request_method = OPTIONS ) {
    return 200;
    }
    root ${project_dir}/public;
    include ${project_dir}/config/nginx/nginx.conf;
}
    ",
    notify  => Service["nginx"]
  }
}