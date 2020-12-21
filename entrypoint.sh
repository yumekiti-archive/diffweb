useradd -s /bin/bash -m ${USER_NAME}
export HOME=/home/${USER_NAME}
usermod -u ${USER_ID} ${USER_NAME}
groupadd -g ${GROUP_ID} ${GROUP_NAME}
usermod -g ${GROUP_NAME} ${USER_NAME}
