# ARK Stats
Statistics for dedicated ARK servers running on docker container
## Prerequisites
This image was built to refer to [jacobpeddk/ark-server-tools](https://hub.docker.com/r/jacobpeddk/ark-server-tools/) for running dedicated ARK servers  
Put the instance configuration, the logs and the server binaries in different persistent volumes. This gives you also the flexibility to run each instance in a container.
Propagate the config volumes and the server binaries as read-only into the arkstats container.

## Build
```
docker build -t rediculum/arkstats .
```
You can find the current image also on [docker hub](https://hub.docker.com/repository/docker/rediculum/arkstats)

## Example using Docker Compose
```
version: "3.5"
services:
  ark_theisland:
    image: "jacobpeddk/ark-server-tools"
    container_name: ark_theisland
    restart: unless-stopped
    ports:
      - 27015:27015/udp
      - 7778:7778/udp
    volumes:
      - "ark_theisland_conf:/ark/configs"
      - "ark_servers:/ark/servers"
      - "ark_theisland_logs:/ark/logs"
  ark_ragnarok:
    image: "jacobpeddk/ark-server-tools"
    container_name: ark_ragnarok
    restart: unless-stopped
    ports:
      - 27016:27016/udp
      - 7780:7780/udp
    volumes:
      - "ark_ragnarok_conf:/ark/configs"
      - "ark_servers:/ark/servers"
      - "ark_ragnarok_logs:/ark/logs"
  arkstats:
    image: "rediculum/arkstats"
    container_name: arkstats
    restart: unless-stopped
    ports:
      - "8888:80"
    volumes:
      - "ark_theisland_conf:/ark/configs/theisland:ro"
      - "ark_ragnarok_conf:/ark/configs/ragnarok:ro"
      - "ark_servers:/ark/servers:ro"
    healthcheck:
      test: ["CMD", "curl", "-sLf" , "http://localhost/health"]
volumes:
  ark_theisland_conf:
  ark_ragnarok_conf:
  ark_theisland_logs:
  ark_ragnarok_logs:
  ark_servers:
```
You can use the arkmanager command inside the container by firing up a bash. Remember also to configure each instance with its proper ports
