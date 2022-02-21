# guzzle-retry

laravel guzzle retry

## Prerequisites
- php 7.1
- guzzlehttp/guzzle
- laravel

## Installation
```sh
$ composer require YangCSir/guzzle-retry
```

use it
```php
$retry=new GuzzleRetry(3,200);

$res = $retry->client->get(Url);

```
