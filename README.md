# Vacation Portal

A simple PHP-based vacation portal with PostgreSQL backend. This guide covers **local deployment** on your machine.

---

## **Local Deployment**

### **Requirements**

- PHP ≥ 8.0
- PostgreSQL
- Composer

---

### **1. Clone the repository TODO**

```bash
git clone REPLACE vacation-portal
cd vacation-portal
```

### **2. Install PHP dependencies**

```bash
composer install
```

### **3. Install & Setup PostgreSQL**
Connect to PostgreSQL:
```bash
psql -U postgres
```
Create the database and user:
```sql
CREATE DATABASE <database name>;
CREATE USER <database user> WITH PASSWORD '<database user password>';
GRANT ALL PRIVILEGES ON DATABASE <database name> TO <database user>;
\q
```
Run schema and seed files:
```bash
psql -U vacation_user -d vacation_portal -f db/schema.sql -h 127.0.0.1 -W
psql -U vacation_user -d vacation_portal -f db/seed.sql -h 127.0.0.1 -W

```


### **3. Configure environment variables**
```bash
cp .env.example .env
```
Edit ```.env``` to set your local database credentials:
```bash
APP_ENV=development
APP_DEBUG=true

DB_HOST=127.0.0.1
DB_PORT=5432
DB_NAME=<database name>
DB_USER=<database user>
DB_PASS=<database user password>
```

### **5. Start the PHP development server**
```bash
php -S localhost:8000 -t public public/index.php
```


