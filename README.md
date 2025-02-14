# E-commerce Store Project

A simple e-commerce store built with PHP and MySQL.

## Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Docker and Kubernetes for deployment

## Environment Variables

Copy `.env.example` to `.env` and set the following variables:

- `DB_HOST`: MySQL host
- `DB_NAME`: Database name
- `DB_USER`: Database user
- `DB_PASSWORD`: Database password

## Development Setup

1. Clone the repository
2. Copy `.env.example` to `.env` and configure
3. Build the Docker image:
   ```bash
   docker build -t php-store .
   ```
4. Deploy to Kubernetes:
   ```bash
   helm install ecommerce-store ./helm
   ```