# Use the official Python image
FROM python:3.9-slim

# Create a non-root user called 'ctf'
RUN useradd -m ctf

# Switch to the 'ctf' user's home directory
WORKDIR /home/ctf/app

# Copy the requirements.txt file
COPY requirements.txt ./

# Install dependencies as root
USER root
RUN apt-get update && apt-get install -y curl && pip install --no-cache-dir -r requirements.txt

# Copy the rest of the application
COPY ./app .

# Change ownership of the app directory to the 'ctf' user
RUN chown -R ctf:ctf /home/ctf/app

# Switch to the 'ctf' user
USER ctf
# Create a flag file with a random string
RUN echo "YAO{H@Mboz0o_ElB0zo0_Kam@n0z0o}" > /home/ctf/flag_$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 16)
# Expose the port the app runs on
EXPOSE 5000

# Command to run the app
CMD ["python", "app.py"]