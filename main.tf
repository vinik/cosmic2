resource "digitalocean_ssh_key" "cosmic_ssh_key" {
  name = "s2deployerkey"
  public_key = "${file("~/.ssh/id_rsa.pub")}"
}

resource "digitalocean_domain" "default" {
    name = "cosmic.waferpie.com"
    ip_address = "${digitalocean_droplet.cosmic.ipv4_address}"
}

# cosmic server
resource "digitalocean_droplet" "cosmic" {
  image = "ubuntu-14-04-x64"
  name = "cosmic.waferpie.com"
  region = "nyc3"
  size = "512mb"
  backups = true
  private_networking = true
  ssh_keys = [ "971438", "978397", "${digitalocean_ssh_key.cosmic_ssh_key.id}" ]

  connection {
    type = "ssh"
    user = "root"
    key_file = "~/.ssh/id_rsa"
  }

  # Installing apache
  provisioner "remote-exec" {
    inline = [
      "apt-get update",
  ]
  }

  # Provisions for knife
//  provisioner "remote-exec" {
//    inline = [
//      "apt-get update",
//      "apt-get install -y --force-yes git",
//      "curl -L https://www.opscode.com/chef/install.sh | sudo bash",
//      "git clone https://github.com/opscode/chef-repo.git",
//      "mkdir -p ~/chef-repo/.chef",
//      "cp ~/.ssh/s2deployer.pem ~/chef-repo/.chef/",
//      "cp ~/.ssh/s2way-validator.pem ~/chef-repo/.chef/"
//    ]
//  }


  # Upload knife.rb
  # provisioner "local-exec" {
  #  command = "scp -o \"StrictHostKeyChecking no\" ./chef/knife.rb root@${digitalocean_droplet.chef-server.ipv4_address}:~/chef-repo/.chef/"
  # }


  # Busca a chave da empresa (s2way-validator.pem)
  provisioner "local-exec" {
    command = "scp -o \"StrictHostKeyChecking no\" root@${digitalocean_droplet.chef-server.ipv4_address}:~/.ssh/s2way-validator.pem ./chef/"
  }
  provisioner "local-exec" {
  	command = "cp ./chef/s2way-validator.pem ./chef/chef-repo/.chef/"
  }

  # Busca a chave do usuário (s2deployer.pem)
  provisioner "local-exec" {
    command = "scp -o \"StrictHostKeyChecking no\" root@${digitalocean_droplet.chef-server.ipv4_address}:~/.ssh/s2deployer.pem ./chef/"
  }

  provisioner "local-exec" {
  	command = "cp ./chef/s2deployer.pem ./chef/chef-repo/.chef/"
  }

//  # upload cookbooks
//  provisioner "remote-exec" {
//    inline = [
//      "cd chef-repo",
//      "knife ssl fetch",
//      "cd cookbooks",
//
//      # chef-handler
//      "knife cookbook site download chef_handler",
//      "tar -xvf chef_handler*",
//      "rm chef_handler*.gz",
//      "knife cookbook upload chef_handler",
//
//      # windows
//      "knife cookbook site download windows",
//      "tar -xvf windows*",
//      "rm windows*.gz",
//      "knife cookbook upload windows",
//
//      #7-zip
//      "knife cookbook site download 7-zip",
//      "tar -xvf 7-zip*",
//      "rm 7-zip*.gz",
//      "knife cookbook upload 7-zip",
//
//      # ark
//      "knife cookbook site download ark",
//      "tar -xvf ark*",
//      "rm ark*.gz",
//      "knife cookbook upload ark",
//
//      # cron
//      "knife cookbook site download cron",
//      "tar -xvf cron*",
//      "rm cron*.gz",
//      "knife cookbook upload cron",
//
//      # logrotate
//      "knife cookbook site download logrotate",
//      "tar -xvf logrotate*",
//      "rm logrotate*.gz",
//      "knife cookbook upload logrotate",
//
//      # chef-client
//      "knife cookbook site download chef-client",
//      "tar -xvf chef-client*",
//      "rm chef-client*.gz",
//      "knife cookbook upload chef-client"
//
//
//       apt,runit,packagecloud,yum,jenkins
//        chef-sugar,openssl,couchbase
//        java,elasticsearch-ng
//        docker
//        homebrew, nodejs
//
//    ]
//  }

  # Configura o arquivo knife.rb
  # provisioner "local-exec" {
  #   command = "cp ./chef/chef_repo/.chef/knife.rb-dist ./chef/chef_repo/.chef/knife.rb"
  # }

  # provisioner "local-exec" {
  #   command = "sed -i 's/https:\/\/chef/https:\/\/${digitalocean_droplet.chef-server.ipv4_address}/g' ./chef/chef_repo/.chef/knife.rb"
  # }

  # provisioner "local-exec" {
  #   command = "cd ./chef/chef-repo/cookbooks & knife cookbook upload ark"
  # }

}

# jenkins server
//resource "digitalocean_droplet" "jenkins" {
//  image = "ubuntu-14-04-x64"
//  name = "jenkins"
//  region = "nyc3"
//  size = "512mb"
//  backups = true
//  private_networking = true
//  ssh_keys = [ "${digitalocean_ssh_key.s2deployer_ssh_key.id}" ]
//
//  connection {
//    type = "ssh"
//    user = "root"
//    key_file = "~/.ssh/id_rsa"
//  }
//
//  provisioner "chef" {
//    run_list = ["chef-client"]
//    node_name = "jenkins"
//    server_url = "https://chef.ckrst.com/organizations/s2way"
//    validation_client_name = "s2way-validator"
//    validation_key_path = "./chef/s2way-validator.pem"
//    version = "12.3.0-1"
//    ssl_verify_mode = ":verify_none"
//  }
//}
