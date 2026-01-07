# Single Sorted Linked List (PHP 8.4)


## 🚀 Quick Start

```bash
# 1. Clone the repository
git clone git@github.com:jakubs-work/shipmonk.git
cd shipmonk

# 2. Install & start everything (recommended) (if already instaled use `make start`
make install

# 3. Run tests
make tests


## 🚀 Manual installation
# Start containers
docker compose up -d 

# Install dependencies
docker compose exec php composer install

# Generate optimized autoloader (optional but recommended)
docker compose exec php composer dump-autoload

# Run static analysis
docker compose exec php vendor/bin/psalm

# Run tests
docker compose exec php vendor/bin/phpunit
