Vagrant.configure("2") do |config|

  config.vm.provider "virtualbox" do |provider, override|
    override.vm.box = "ubuntu/trusty64"
    override.vm.network "private_network", ip: "100.87.136.136"
    override.vm.synced_folder "", "/www/nebo15.gandalf.api",
        owner: "vagrant", group: "vagrant"
    provider.gui = false
    provider.customize ["modifyvm", :id, "--memory", "512"]
    provider.customize ["modifyvm", :id, "--cpuexecutioncap", "25"]
    provider.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    provider.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
    override.vm.provision "shell", inline: $virtualbox_script_app
  end

end

$virtualbox_script_app = <<SCRIPT
#!/bin/bash
set -o nounset -o errexit -o pipefail -o errtrace
trap 'error "${BASH_SOURCE}" "${LINENO}"' ERR
TIMEZONE="Europe/Kiev"
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
echo 127.0.0.1 mbill.dev | sudo tee -a /etc/hosts
SCRIPT
