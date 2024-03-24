FROM node:20 as npm-base
LABEL maintainer="Thornton Phillis (dev@th0rn0.co.uk)"

RUN apt update

RUN apt install -y wget

RUN wget https://www.python.org/ftp/python/2.7.9/Python-2.7.9.tgz
RUN tar xzf Python-2.7.9.tgz
RUN cd Python-2.7.9 && ./configure --enable-optimizations && make altinstall
# RUN ./configure --enable-optimizations
# RUN make altinstall

RUN ln -sfn '/usr/local/bin/python2.7' '/usr/bin/python2'
RUN update-alternatives --install /usr/bin/python python /usr/bin/python2 1
WORKDIR /app