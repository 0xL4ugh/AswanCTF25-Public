FROM ubuntu:20.04
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip \
    socat

RUN pip3 install pycryptodome

WORKDIR /app
COPY server.py .
COPY start.sh .

RUN chmod +x start.sh
RUN python3 --version


EXPOSE 1333
ENTRYPOINT ["./start.sh"]

