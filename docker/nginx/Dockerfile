# =====================================================================
# Nginx Static Asset & Proxy Container for SolarShare
# =====================================================================
FROM node:20-alpine AS assets
WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY vite.config.js ./
COPY resources/ resources/
COPY public/ public/
RUN npm run build

FROM nginx:alpine

# Copy custom Nginx configuration
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy public static assets and compiled Vite bundles
COPY public/ /var/www/html/public/
COPY --from=assets /app/public/build /var/www/html/public/build

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
