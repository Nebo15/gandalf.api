#!/usr/bin/env bash
modules_dir=$(dirname $0)/../../modules
cd ${modules_dir}/../..
project_dir=$(pwd)
new_relic_key=1231231231312312312
LOCALE_LANGUAGE="en_US"
LOCALE_CODESET="en_US.UTF-8"
sudo locale-gen ${LOCALE_LANGUAGE} ${LOCALE_CODESET}
sudo echo "export LANGUAGE=${LOCALE_CODESET}
export LANG=${LOCALE_CODESET}
export LC_ALL=${LOCALE_CODESET} " | sudo tee --append /etc/bash.bashrc
echo ${TIMEZONE} | sudo tee /etc/timezone
export LANGUAGE=${LOCALE_CODESET}
export LANG=${LOCALE_CODESET}
export LC_ALL=${LOCALE_CODESET}
sudo dpkg-reconfigure locales

show_help()
{
cat << EOF
This script download files from remote server and download tar.gz to local machine or remote host.
Usage: $0 options
OPTIONS:
    -u  new_relic_key
    -h  show this message
EOF
}

while getopts "n:h:" OPTION
do
     case ${OPTION} in
         n)
             new_relic_key=$OPTARG
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
fi;

if [ ! -e /www ]; then
    sudo mkdir /www/
    sudo chmod 755 /www/
    sudo chown www-data:www-data /www/
    sudo mkdir -p /var/www/.ssh
    sudo chown -Rf www-data:www-data /var/www/
fi;

if [ ! -e ${modules_dir}/stdlib ]; then
    sudo puppet module install --force puppetlabs/stdlib --target-dir ${modules_dir}
fi;
if [ ! -e ${modules_dir}/apt ]; then
    sudo puppet module install --force puppetlabs/apt --target-dir ${modules_dir}
fi;
if [ ! -e ${modules_dir}/wget ]; then
    sudo puppet module install --force maestrodev/wget --target-dir ${modules_dir}
fi;
if [ ! -e ${modules_dir}/composer ]; then
    sudo puppet module install --force willdurand/composer --target-dir ${modules_dir}
fi;
if [ ! -e ${modules_dir}/timezone ]; then
    sudo puppet module install --force saz-timezone --target-dir ${modules_dir}
fi;
if [ ! -e ${modules_dir}/locales ]; then
    sudo puppet module install --force saz-locales --target-dir ${modules_dir}
fi;
if [ ! -e ${modules_dir}/accounts ]; then
    sudo puppet module install --force puppetlabs-accounts --target-dir ${modules_dir}
fi;


sudo puppet apply --modulepath ${modules_dir} ${modules_dir}/../manifests/production/init.pp

if [ ! -e /etc/ssl/dhparam.pem ]
then
    sudo openssl dhparam -out /etc/ssl/dhparam.pem 4096
fi;

if [ ! -e ${modules_dir}/nginx ]; then
    sudo puppet module install --force jfryman-nginx --target-dir ${modules_dir}
fi;

sudo FACTER_newrelic_key="${new_relic_key}" puppet apply --modulepath ${modules_dir} ${modules_dir}/../manifests/production/general.pp