node default {
  include stdlib
  include apt

  $newrelic_app_name = 'gandalf.api.production'

  class { 'php70':
    user            => 'deploybot',
    group           => 'deploybot',
    error_repotring => 0
  } ->
  file { ["/www", "/var/www", "/var/www/.ssh", "/var/log", "/var/log/www"]:
    ensure => "directory",
    owner  => "deploybot",
    group  => "deploybot",
    mode   => 755
  } ->

  class { 'newrelic::server::linux':
    newrelic_license_key  => $newrelic_key,
  } ~>
  class { 'newrelic::agent::php':
    newrelic_license_key  => $newrelic_key,
    newrelic_ini_appname  => $newrelic_app_name,
    newrelic_php_conf_dir => ['/etc/php/7.0/mods-available'],
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
    gzip                => 'off'
  } ->
  file { "/etc/nginx/conf.d/ssl.conf":
    content => "\
ssl_dhparam /etc/ssl/dhparam.pem;
  ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
  ssl_ciphers 'ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-DSS-AES128-GCM-SHA256:kEDH+AESGCM:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA384:ECDHE-RSA-AES256-SHA:ECDHE-ECDSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:DHE-DSS-AES128-SHA256:DHE-RSA-AES256-SHA256:DHE-DSS-AES256-SHA:DHE-RSA-AES256-SHA:AES128-GCM-SHA256:AES256-GCM-SHA384:AES128-SHA256:AES256-SHA256:AES128-SHA:AES256-SHA:AES:CAMELLIA:DES-CBC3-SHA:!aNULL:!eNULL:!EXPORT:!DES:!RC4:!MD5:!PSK:!aECDH:!EDH-DSS-DES-CBC3-SHA:!EDH-RSA-DES-CBC3-SHA:!KRB5-DES-CBC3-SHA';
  ssl_session_cache shared:SSL:10m;
  ssl_session_timeout 10m;
  ssl_prefer_server_ciphers on;
",
    mode    => 0440,
    owner   => root,
    group   => root,
  }


  cron { "/usr/bin/php /www/gandalf.api/current/artisan schedule:run >> /dev/null 2>&1":
    command => "/usr/bin/php /www/gandalf.api/current/artisan schedule:run >> /dev/null 2>&1",
    user    => deploybot,
    ensure  => present,
    hour    => "*",
    minute  => "*",
    month   => "*"
  }

  file { "gandalf_config":
    path    => "/etc/nginx/sites-enabled/gandalf.api.conf",
    content => "
    server {
    listen 80 default_server;
    server_name api.gndf.io;
    rewrite ^/(.*)$ https://api.gndf.io/\$1 permanent;
}

server {
    listen 443 ssl;
    error_log /var/log/nginx.log;
    server_name api.gndf.io;
    ssl_certificate      /etc/ssl/STAR_gndf_io.crt;
    ssl_certificate_key  /etc/ssl/STAR_gndf_io.key;
    ssl on;
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
  notify = > Service["nginx"]
  }
}