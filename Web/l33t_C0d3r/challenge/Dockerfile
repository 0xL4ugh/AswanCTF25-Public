# Use Python as the base image
FROM python:3.10-slim

# Set working directory inside container
WORKDIR /app

# Install build tools for compiling C++ code
RUN apt-get update && apt-get install -y g++ && rm -rf /var/lib/apt/lists/*

# Copy files into container
COPY . .

# Compile the C++ code
RUN g++ server.cpp -o server

# Install Python dependencies
RUN pip install flask

# Expose the Flask port
EXPOSE 5000

# Run the Flask app
CMD ["python", "app.py"]