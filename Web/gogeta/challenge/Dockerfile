FROM golang:1.9.4

# Fix outdated Debian repos
RUN sed -i 's/deb.debian.org/archive.debian.org/g' /etc/apt/sources.list && \
    sed -i 's|security.debian.org|archive.debian.org|g' /etc/apt/sources.list && \
    sed -i '/stretch-updates/d' /etc/apt/sources.list && \
    sed -i '/stretch\/updates/d' /etc/apt/sources.list && \
    echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99no-check-valid-until

# Install only required tools
RUN apt-get update && apt-get install -y mercurial curl

# Working dir
WORKDIR /app

# Drop flag for exploitation
RUN echo "YAO{1m_4_5up3r_541y4n_blu3_g0g3t4}" > /root/flag.txt

# Copy challenge files
COPY ./index.html ./main.go ./

# Build the Go server
RUN go build -o server main.go

# Run it
CMD ["./server"]
