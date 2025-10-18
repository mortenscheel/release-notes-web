FROM nginx:alpine

# Copy custom nginx config
COPY default.conf /etc/nginx/conf.d/default.conf

# Ensure proper permissions
RUN chmod 644 /etc/nginx/conf.d/default.conf

EXPOSE 80
