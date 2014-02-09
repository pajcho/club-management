# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.vm.box = "gkdif.local"

    config.vm.box_url = "http://files.vagrantup.com/precise64.box"

    config.vm.network :forwarded_port, guest: 80, host: 8085

	# config.vm.network :forwarded_port, guest: 3306, host: 3306
	config.vm.network :private_network, ip: "192.168.56.102"

    config.vm.provision :shell, :path => "install.sh"

	# Optionally customize amount of RAM
	# allocated to the VM. Default is 384MB
	config.vm.provider :virtualbox do |vb|

		vb.customize ["modifyvm", :id, "--memory", "768"]

	end
	
    # If true, then any SSH connections made will enable agent forwarding.
    # Default value: false
    # config.ssh.forward_agent = true

    # Share an additional folder to the guest VM. The first argument is
    # the path on the host to the actual folder. The second argument is
    # the path on the guest to mount the folder. And the optional third
    # argument is a set of non-required options.
    # config.vm.synced_folder "../data", "/vagrant_data"
end