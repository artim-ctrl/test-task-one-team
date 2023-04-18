### Test Task 18.04.23

## How to start

```bash
docker compose up -d app mysql nginx
```

# Connect to the app
```bash
docker compose exec app /bin/sh
```

# Initialize the app (configs)
```bash
php init --env=Development --overwrite=All --delete=All
```

# Migrate the database
```bash
php yii migrate --interactive=0 && php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0
```

# Create roles
```bash
php yii rbac/init
```

# Create the admin
```bash
php yii rbac/create-admin <username> <email> <password>
```
For example:
```bash
php yii rbac/create-admin admin admin@admin.com admin
```

# Create users
```bash
php yii rbac/create-users <count-fake-users>
```
For example:
```bash
php yii rbac/create-users 10
```

# Go To:
```
http://localhost:3000/site/calendar
```