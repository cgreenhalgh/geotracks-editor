Vagrant.configure(2) do |config|
    config.vm.box = "ubuntu/trusty64"

  config.vm.provider "virtualbox" do |v|
    v.memory = 1024
  end

  # web server for angular test
  config.vm.network "forwarded_port", guest: 80, host: 8080

  config.vm.provision "shell", privileged: false, inline: <<-SHELL
    sudo apt-get update
    sudo apt-get install -y git wget curl

    # saltstack setup for apache/php/wordpress...
    [ -d /srv ] ||  sudo mkdir /srv && sudo chown vagrant /srv
    cd /srv
    [ -d formulas ] || mkdir /srv/formulas
    cd /srv/formulas
    # note: update md5 checksum if changing release
    [ -d apache-formula ] || wget -O apache-formula-20160218.tar.gz https://github.com/cgreenhalgh/apache-formula/archive/20160218.tar.gz && md5sum -c /vagrant/md5/apache-formula.md5 && tar zxf apache-formula-20160218.tar.gz && mv apache-formula-20160218 apache-formula
    [ -d mysql-formula ] || wget -O mysql-formula-20160225.tar.gz https://github.com/cgreenhalgh/mysql-formula/archive/20160225.tar.gz && md5sum -c /vagrant/md5/mysql-formula.md5 && tar zxf mysql-formula-20160225.tar.gz && mv mysql-formula-20160225 mysql-formula
    [ -d php-formula ] || wget -O php-formula-20160229.tar.gz https://github.com/cgreenhalgh/php-formula/archive/20160229.tar.gz && md5sum -c /vagrant/md5/php-formula.md5 && tar zxf php-formula-20160229.tar.gz && mv php-formula-20160229 php-formula
    # TODO: switch to release when stable
    [ -d selfservice-formula ] || git clone https://github.com/cgreenhalgh/selfservice-formula.git

    # saltstack
    # see http://docs.saltstack.com/en/latest/topics/installation/ubuntu.html
    sudo add-apt-repository -y ppa:saltstack/salt
    sudo apt-get update
    sudo apt-get install -y salt-minion

    # saltstack config...
    sudo cp /vagrant/saltstack/etc/minion-local-dev.conf /etc/salt/minion
    sudo service salt-minion restart
    sudo salt-call state.highstate

    # Node.js
    curl -sL https://deb.nodesource.com/setup_4.x | sudo bash -
    sudo apt-get install -y nodejs
    #sudo apt-get install -y build-essential

    # --no-bin-links workaround for use on top of windows FS
    #npm install --no-bin-links

    # bower
    sudo npm install -g bower
    cd /vagrant
    bower install

    sudo npm install -g coffee-script

SHELL


end

