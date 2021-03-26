from debian:latest   
MAINTAINER CARLOS carloscaraccio@gmail.com 
RUN apt-get update 
RUN apt-get -y install wget lsb-release gnupg 
RUN apt-get -y install php 
RUN wget https://dev.mysql.com/get/mysql-apt-config_0.8.16-1_all.deb 
RUN wget https://dev.mysql.com/get/Downloads/MySQL-8.0/mysql-community-server-core_8.0.23-1debian10_amd64.deb   
RUN apt-get install libaio1 libmecab2 libnuma1 
RUN dpkg -i mysql-apt-config_0.8.16-1_all.deb 
RUN dpkg -i mysql-community-server-core_8.0.23-1debian10_amd64.deb 
RUN apt-get update 
expose 3306 
