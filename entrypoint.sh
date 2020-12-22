useradd -s /bin/bash -m yume
export HOME=/home/yume
usermod -u 1000 yume
groupadd -g 1000 yume
usermod -g yume yume
