FROM nginx:bookworm
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl && \
    rm -rf /var/lib/apt/lists/*
COPY nginx.conf /etc/nginx/nginx.conf
EXPOSE 80
