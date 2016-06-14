node default {
  include stdlib
  include apt

  $newrelic_app_name = 'gandalf.api.production'

  class { 'php56':
    user            => 'deploybot',
    group           => 'deploybot',
    error_repotring => 0
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
    daemon_user         => 'deploybot',
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
    gzip                => 'off',
    ssl_dhparam         => '/etc/ssl/dhparam.pem'
  }


  file { "gandalf_config":
    path    => "/etc/nginx/sites-enabled/gandalf.api.conf",
    content => "
    server {
    listen 80;
    error_log /var/log/nginx.log;
    server_name gndf.io;
    add_header 'Access-Control-Allow-Origin' *;
    add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS, PUT, DELETE';
    add_header 'Access-Control-Allow-Headers' 'Authorization,Content-Type,Accept,Origin,User-Agent,DNT,Cache-Control,Keep-Alive,If-Modified-Since,X-Project-ID,X-Date,X-Accept-Charset,X-Application-ID,X-Device-Information,X-Application-Secret-Hash,X-Device-Push-Token,X-Application';
    add_header 'X-Frame-Options' 'DENY';
    if (\$request_method = OPTIONS ) {
    return 200;
    }
    root /www/gandalf.api/current/public;
    include /www/gandalf.api/current/config/nginx/nginx.conf;
}
    ",
    notify  => Service["nginx"]
  }
}