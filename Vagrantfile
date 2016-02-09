Vagrant.configure(2) do |config|
    config.vm.box = "ubuntu/trusty64"

  config.vm.provider "virtualbox" do |v|
    v.memory = 1024
  end

  # web server for angular test
  config.vm.network "forwarded_port", guest: 8000, host: 8000

  config.vm.provision "shell", privileged: false, inline: <<-SHELL
    sudo apt-get install -y git wget curl

    # Node.js
    curl -sL https://deb.nodesource.com/setup_4.x | sudo bash -
    sudo apt-get install -y nodejs
    sudo apt-get install -y build-essential

    # node dependencies
    # --no-bin-links workaround for use on top of windows FS
    #npm install --no-bin-links

SHELL


end

