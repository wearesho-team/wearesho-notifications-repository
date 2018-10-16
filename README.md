# Wearesho Notifications Repository
[![Build Status](https://travis-ci.org/wearesho-team/wearesho-notifications-repository.svg?branch=master)](https://travis-ci.org/wearesho-team/wearesho-notifications-repository)
[![codecov](https://codecov.io/gh/wearesho-team/wearesho-notifications-repository/branch/master/graph/badge.svg)](https://codecov.io/gh/wearesho-team/wearesho-notifications-repository)

This library represents a flexible adapter between your project and [Wearesho Notifications](https://github.com/wearesho-team/wearesho-notifications).
You can either create user`s authorization token for connecting to notifications server using sockets,
or push a notification to it.

## Setup

```bash
composer require wearesho-team/wearesho-notifications-repository
```

## Usage

### Config

To deal with it, you should create [ConfigInterface](./src/ConfigInterface.php).
There are two internal implementations, but you can also implement it by yourself.

```php
<?php

/**
 * @var string $requestsUrl         URL to your notification server.
 * @var string|null $serviceKey     Access key to notification server. Optional (depends on server requirements). 
 */

$config = new Wearesho\Notifications\Config($requestsUrl, $serviceKey);

```

If you prefer environment configuration, you can use [EnvironmentConfig](./src/EnvironmentConfig.php)

```dotenv
WEARESHO_NOTIFICATIONS_URL=https://your.notification.server/
WEARESHO_NOTIFICATIONS_SERVICE_KEY='your personal service key, optional'
```

```php
<?php

$config = new Wearesho\Notifications\EnvironmentConfig($dotenvPrefix = 'WEARESHO_NOTIFICATIONS_');

```

### Create a repository instance

```php
<?php

/**
 * @var Wearesho\Notifications\ConfigInterface $config
 * @var GuzzleHttp\ClientInterface $guzzleClient
 */

$repository = new Wearesho\Notifications\Repository($config, $guzzleClient);

```

### Authorize

This method takes user`s ID and returns authorization token for connection

```php
<?php

/**
 * @var Wearesho\Notifications\Repository $repository
 * @var int $userId 
 */

try {
    $authorizationToken = $repository->authorize($userId);
} catch (Wearesho\Notifications\Exceptions\Credentials\Missed $exception) {
    // Your server requires service key, but you have not passed it in config
} catch (Wearesho\Notifications\Exceptions\Credentials\Invalid $exception) {
    // Your service key is invalid
} catch (Wearesho\Notifications\Exceptions\InvalidResponse $exception) {
    // Unexpected service response.
    // You can receive response instance using $exception->getResponse()
}

```

### Push notification

Firstly you need to create a notification entity

```php
<?php

/**
 * @var int $userId             Notification's owner
 * @var string $message         Notification's content
 * @var array|null $context     Special params for message.
 * F.e. if message is like 'Hello, {person}', you can pass params like [ 'person' => 'Jonh', ]
 * This params can be applied in front-end
 * 
 * @var string|null $type       Notification type.
 * Can be any string. but we recommend to use Wearesho\Notifications\Notification\Type constants
 * to avoid unexpected situations.
 *
 * @var \DateTime|null $time    Notification's creation date
 * @var bool|null $isRead       Mark if the notification is read.
 */

$notification = new Wearesho\Notifications\Notification(
    $userId,
    $message,
    $context,
    $type,
    $time,
    $isRead
);

```

Then pass it to repository

```php
<?php

/**
 * @var Wearesho\Notifications\Notification $notification
 * @var Wearesho\Notifications\Repository $repository
 */

try {
    $repository->push($notification);
} catch (Wearesho\Notifications\Exceptions\Credentials\Missed $exception) {
    // Your server requires service key, but you have not passed it in config
} catch (Wearesho\Notifications\Exceptions\Credentials\Invalid $exception) {
    // Your service key is invalid
} catch (Wearesho\Notifications\Exceptions\InvalidNotification $exception) {
    // You have been formed invalid notification
    // You can receive it using $exception->getNotification()
}

```

#### Push chain
You can push notifications to different notification servers 
using [Push\Chain](./src/Push/Chain.php).
It's quite useful in use in pair with [Push\Filter](./src/Push/Filter.php).

```php
<?php

use Wearesho\Notifications;

/** @var Notifications\Repository $repoFirst */
/** @var Notifications\Repository $repoSecond */

$chain = new Notifications\Push\Chain([
    new Notifications\Push\Filter(
        $repoFirst,
        $types = [
            'primary',
        ]
    ),
    $repoSecond
]);

/** @var Notifications\Notification $notification */

$chain->push($notification);

```
In this example notifications will be pushed to first and second repository
if notification has `primary` type, or only to second if notification has
another type.
