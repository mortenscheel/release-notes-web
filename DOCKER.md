# Docker Setup Guide

This guide explains how to run the Release Notes application using Docker with PostgreSQL and Redis.

## Prerequisites

- Docker Engine 20.10+
- Docker Compose 2.0+

## Services

The docker-compose setup includes the following services:

- **app** - Laravel PHP-FPM application (PHP 8.2)
- **nginx** - Web server (port 8000)
- **postgres** - PostgreSQL 16 database (port 5432)
- **redis** - Redis 7 cache (port 6379)
- **queue** - Laravel queue worker
- **scheduler** - Laravel task scheduler

## Quick Start

### 1. Environment Setup

Copy the Docker environment file:

```bash
cp .env.docker .env
```

Edit `.env` and set your configuration:
- `APP_KEY` - Generate using `php artisan key:generate` (do this after first build)
- `GITHUB_TOKEN` - Your GitHub personal access token (if using GitHub integration)
- Database credentials (defaults are fine for development)

### 2. Build and Start Services

Build the Docker images:

```bash
docker-compose build
```

Start all services:

```bash
docker-compose up -d
```

### 3. Application Setup

Generate application key:

```bash
docker-compose exec app php artisan key:generate
```

Run database migrations:

```bash
docker-compose exec app php artisan migrate --force
```

Optionally, seed the database:

```bash
docker-compose exec app php artisan db:seed
```

### 4. Access the Application

Open your browser and navigate to:

```
http://localhost:8000
```

## Common Commands

### View logs

```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f postgres
docker-compose logs -f redis
docker-compose logs -f queue
```

### Execute artisan commands

```bash
docker-compose exec app php artisan [command]
```

Examples:
```bash
# Clear cache
docker-compose exec app php artisan cache:clear

# Run migrations
docker-compose exec app php artisan migrate

# Create a new user
docker-compose exec app php artisan tinker
```

### Access the database

```bash
# Using psql
docker-compose exec postgres psql -U laravel -d release_notes

# Using Docker exec
docker exec -it release-notes-postgres psql -U laravel -d release_notes
```

### Access Redis CLI

```bash
docker-compose exec redis redis-cli
```

### Restart services

```bash
# Restart all services
docker-compose restart

# Restart specific service
docker-compose restart app
docker-compose restart queue
```

### Stop services

```bash
docker-compose down
```

### Stop services and remove volumes (⚠️ deletes database data)

```bash
docker-compose down -v
```

## Development vs Production

### Development Mode

For development with hot reload, you may want to:

1. Mount the entire codebase as a volume
2. Run `npm run dev` locally instead of building assets
3. Set `APP_DEBUG=true` in `.env`

Add to `docker-compose.yml` under `app` service:

```yaml
volumes:
  - .:/var/www/html
  - /var/www/html/vendor
  - /var/www/html/node_modules
```

### Production Mode

The current setup is optimized for production:

- Multi-stage Docker build for smaller images
- Pre-built assets
- Optimized composer autoloader
- Health checks for dependencies
- Separate queue worker and scheduler containers

## Troubleshooting

### Permission issues

If you encounter permission errors with storage or cache:

```bash
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Database connection issues

Ensure PostgreSQL is ready:

```bash
docker-compose exec postgres pg_isready -U laravel -d release_notes
```

### Redis connection issues

Check Redis connectivity:

```bash
docker-compose exec redis redis-cli ping
```

### Queue not processing

Check queue worker logs:

```bash
docker-compose logs -f queue
```

Restart the queue worker:

```bash
docker-compose restart queue
```

### Clear all caches

```bash
docker-compose exec app php artisan optimize:clear
```

## Database Backups

### Create a backup

```bash
docker-compose exec postgres pg_dump -U laravel release_notes > backup.sql
```

### Restore from backup

```bash
cat backup.sql | docker-compose exec -T postgres psql -U laravel -d release_notes
```

## Updating the Application

1. Pull latest changes
2. Rebuild images: `docker-compose build`
3. Run migrations: `docker-compose exec app php artisan migrate --force`
4. Restart services: `docker-compose restart`

## Environment Variables

Key environment variables in `.env`:

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_ENV` | Application environment | production |
| `APP_DEBUG` | Enable debug mode | false |
| `DB_HOST` | Database host | postgres |
| `DB_DATABASE` | Database name | release_notes |
| `DB_USERNAME` | Database user | laravel |
| `DB_PASSWORD` | Database password | secret |
| `REDIS_HOST` | Redis host | redis |
| `CACHE_STORE` | Cache driver | redis |
| `SESSION_DRIVER` | Session driver | redis |
| `QUEUE_CONNECTION` | Queue driver | redis |

## Ports

| Service | Internal Port | External Port |
|---------|---------------|---------------|
| Nginx | 80 | 8000 |
| PostgreSQL | 5432 | 5432 |
| Redis | 6379 | 6379 |

## Security Notes

⚠️ **Important for production:**

1. Change all default passwords in `.env`
2. Use strong passwords for `DB_PASSWORD`
3. Don't expose PostgreSQL and Redis ports externally (remove `ports:` from docker-compose.yml)
4. Set `APP_DEBUG=false`
5. Configure proper `APP_URL`
6. Use HTTPS with a reverse proxy (like Traefik or Nginx Proxy Manager)
7. Regularly update Docker images and dependencies

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Docker Documentation](https://docs.docker.com/)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)
- [Redis Documentation](https://redis.io/documentation)
