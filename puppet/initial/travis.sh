#!/usr/bin/env bash
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
    sudo apt-get update
fi;

if [ ! -e /www ]; then
    sudo mkdir /www/
    sudo chmod 755 /www/
    sudo chown www-data:www-data /www/
    sudo mkdir -p /var/www/.ssh
    sudo chown -Rf www-data:www-data /var/www/
fi;

sudo puppet module install puppetlabs/stdlib --target-dir /www/gandalf.api/puppet/modules
sudo puppet module install puppetlabs/apt --target-dir /www/gandalf.api/puppet/modules
sudo puppet module install maestrodev/wget --target-dir /www/gandalf.api/puppet/modules
sudo puppet module install willdurand/composer --target-dir /www/gandalf.api/puppet/modules
sudo puppet module install jfryman-nginx --target-dir /www/gandalf.api/puppet/modules

sudo FACTER_server_tags="role:travis" puppet apply --modulepath /www/gandalf.api/puppet/modules /www/gandalf.api/puppet/general/manifests/init.pp