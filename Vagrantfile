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
echo 127.0.0.1 gandalf.dev | sudo tee -a /etc/hosts
sudo /bin/bash /vagrant/puppet/initial/init.sh -u "www-data"
SCRIPT
