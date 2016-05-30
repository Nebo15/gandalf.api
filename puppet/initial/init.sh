#!/usr/bin/env bash
modules_dir=$(dirname $0)/../modules
cd ${modules_dir}/../..
project_dir=$(pwd)
daemon_user='deploybot'
newrelic_key="1234567890123456789012345678901234567890"
newrelic_app_name="test-new-relic-app-name"

show_help()
{
cat << EOF
This script download files from remote server and download tar.gz to local machine or remote host.
Usage: $0 options
OPTIONS:
    -u  daemon user
    -k  newrelic licence key
    -n  newrelic app name
    -h  show this message
EOF
}

while getopts "u:h:n:k:" OPTION
do
     case ${OPTION} in
         u)
             daemon_user=$OPTARG
             ;;
         k)
              newrelic_key=$OPTARG
             ;;
         n)
              newrelic_app_name=$OPTARG
             ;;
         h)
             show_help
             ;;
         ?)
             show_help
             exit
             ;;
     esac
done

if [ ! -e /usr/bin/puppet ]; then
    source /etc/lsb-release
    wget https://apt.puppetlabs.com/puppetlabs-release-$DISTRIB_CODENAME.deb
    sudo dpkg -i puppetlabs-release-$DISTRIB_CODENAME.deb
    rm puppetlabs-release-$DISTRIB_CODENAME.deb
    sudo apt-get update
    sudo apt-get install -y -f puppet git
    if [ ! -d "/etc/puppet/environments" ]; then
        sudo mkdir /etc/puppet/environments;
    fi
    sudo chgrp puppet /etc/puppet/environments
    sudo chmod 2775 /etc/puppet/environments
    echo '
    START=yes
    DAEMON_OPTS=""
    ' | sudo tee --append /etc/default/puppet
    sudo service puppet start
else
    echo 1;
    #sudo apt-get update
fi;

if [ ! -e /www ]; then
    sudo mkdir /www/
    sudo chmod 755 /www/
    sudo chown www-data:www-data /www/
    sudo mkdir -p /var/www/.ssh
    sudo chown -Rf www-data:www-data /var/www/
fi;

sudo puppet module install --force puppetlabs/stdlib --target-dir ${modules_dir}
sudo puppet module install --force puppetlabs/apt --target-dir ${modules_dir}
sudo puppet module install --force maestrodev/wget --target-dir ${modules_dir}
sudo puppet module install --force willdurand/composer --target-dir ${modules_dir}
sudo puppet module install --force jfryman-nginx --target-dir ${modules_dir}
sudo puppet module install --force saz-timezone --target-dir ${modules_dir}
sudo puppet module install --force saz-locales --target-dir ${modules_dir}
sudo puppet module install --force fsalum-newrelic --target-dir ${modules_dir}

sudo FACTER_project_dir="${project_dir}" FACTER_newrelic_app_name="${newrelic_app_name}"  FACTER_newrelic_key="${newrelic_key}" FACTER_daemon_user="${daemon_user}" FACTER_error_reporting="0" puppet apply --modulepath ${modules_dir} ${modules_dir}/../general/manifests/init.pp