# Description

Example of Symfony application to generate loan issue logic.

# Requirements

- composer
- php 8.2

# Setup

```composer install```

# Run

All scenarios are run through the CLI commands.

**NOTE**: There are predefined users and products in the file database, check the `/db` folder.

### Create user
```bin/console user:create```
### Update user
```bin/console user:update [user_uuid]```
### Check product probability for user
```bin/console product:check [product_uuid] [user_uuid]```
### Issue product probability for user
```bin/console product:issue [product_uuid] [user_uuid]```

# Tests & code-style check

## Run tests

```composer tests```

## Run code-style check

```composer ecs ```

```composer phpstan```

```composer psalm```

## Altogether

```composer check-all```
