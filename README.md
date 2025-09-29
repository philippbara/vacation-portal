# Vacation Portal

A simple PHP-based vacation portal with PostgreSQL and Vue.js. Main features include:

- **Authentication**: Login functionality, password hashing, etc.
- **Authorization**: Role-based access, currently support admin, manager and employee roles
- **User management**: Managers can view, edit and delete employees
- **Vacation request management**: Employees can submit new vacation requests, view their own requests and delete pending ones. Manager can approve or reject requests.
- **Sorting**: Vacation requests are sorted based on the closest to today's date
- **Basic checks**: Date is in future for vacation requests, email and employee_code format, etc.
- **DB seeding**: PostgreSQL can be seeded automatically to make testing easier

### **Project Structure**
- **config/** — configuration files (database, environment, app settings)
- **db/** — database schema and seed files
- **public/** — publicly accessible files (entry point)
- **src/** — core application source code
    - **helpers/** — utility/helper functions
    - **models/** — database models
    - **routes/** — route definitions
    - **views/** — HTML/PHP templates
- **.env** — environment variables
- **composer.json** — PHP dependencies
- **README.md** — project documentation


---

## **Docker Deployment (Recommended)**

### **1. Requirements**
- [Docker](https://docs.docker.com/engine/install/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### **2. Clone the repository TODO**

```bash
git clone git@github.com:philippbara/vacation-portal.git
cd vacation-portal
```

### **3. Configure the environment**
Copy the example ```.env```:
```bash
cp .env.example .env
```
(Optional) Edit ```.env``` if you want. If you don't the containers will be started with the default values

### **4. Build and start containers**

```bash
docker-compose up --build
```

This will start the PHP server and the PostgreSQL database and automatically seed the database with the values from
```db/02_seed.sql```

Make sure that port 5432 and 8000 are free and you don't have any other services running locally.

The portal will be available at: http://localhost:8000.

You can test the main features with these accounts:

| Username   | Password       |
|------------|----------------|
| manager1   | manager1pass   |
| employee1  | employee1pass  |

See ```db/02_seed.sql``` for more accounts.

### **5. Reset the database (optional)**
If you want to completely restart the database (drop volumes included):
```bash
docker-compose down -v
docker-compose up --build
```
This will remove all database data and rebuild the containers.

---
## **Local Deployment (Linux)**

### **0. Setup clean environment (Optional)**
Although this guide creates a new postgreSQL database and shouldn't contaminate your local setup you might still want to opt for a clean isolated environment in a docker conatiner. You can start one with ubuntu:latest
```bash
docker run -it -p 8000:8000 --name test-ubuntu ubuntu:latest bash
```

### **1. Install requirements (tested on a clean ubuntu:latest docker image)**

```bash
apt-get update
apt-get install -y sudo php-common libapache2-mod-php php-cli composer postgresql php-xml

# you will be asked to select a locale
```

### **2. Clone the repository**

```bash
git clone https://github.com/philippbara/vacation-portal.git
cd vacation-portal
```

### **3. Install PHP dependencies**

```bash
composer install
```

### **4. Install & Setup PostgreSQL**
This guide creates a vacation_portal database that can be accessed by vacation_user with the password vacation. Change the values and the commands accordingly. If you plan to use your own local instance of PostgreSQL you might need to adjust the commands and the ```.env``` file in the next step.

Start and/or connect to PostgreSQL:
```bash
service postgresql start
sudo -su postgres
psql
```
Create the database and user:
```sql
-- Create database and user
CREATE DATABASE vacation_portal;
CREATE USER vacation_user WITH PASSWORD 'vacation';

-- Give full control of the database
GRANT ALL PRIVILEGES ON DATABASE vacation_portal TO vacation_user;

-- Switch to the vacation_portal database
\c vacation_portal

-- Give privileges on the public schema
GRANT ALL PRIVILEGES ON SCHEMA public TO vacation_user;

-- (Optional but recommended) make vacation_user the schema owner
ALTER SCHEMA public OWNER TO vacation_user;
\q
```
Run schema and seed files.
```bash
psql -U vacation_user -d vacation_portal -f db/01_schema.sql -h 127.0.0.1 -W

# Input password "vacation"

psql -U vacation_user -d vacation_portal -f db/02_seed.sql -h 127.0.0.1 -W

# Input password "vacation"
```


### **3. Configure environment variables**
```bash
cp .env.example .env
```
Only edit ```.env``` if you don't use the database created in this guide.

### **5. Start the PHP development server**
```bash
php -S 0.0.0.0:8000 -t public public/router.php
```

The portal will be available at: http://localhost:8000.

You can test the main features with these accounts:

| Username   | Password       |
|------------|----------------|
| manager1   | manager1pass   |
| employee1  | employee1pass  |

See ```db/02_seed.sql``` for more accounts.


