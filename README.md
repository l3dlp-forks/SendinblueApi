Sendinblue API
==============

[![Build Status](https://travis-ci.org/ScoringLine/SendinblueApi.svg?branch=master)](https://travis-ci.org/ScoringLine/SendinblueApi)

Clean and simple to use lib for use Sendinblue API.

- [x] tested
- [x] stable
- [x] extensible


> For now the only API available is the SMS api, more is coming later, PRs accepted with love.

Installation
------------

**Requirements:**

* PHP 5.4+
* composer

Launch the following command to install:

```bash
$ composer require scoringline/sendinblue-api
```

Authentication
--------------

```php
<?php

require 'vendor/autoload.php';

use Scoringline\SendinblueApi\Sendinblue;

$sendinblue = new Sendinblue();

$sendinblue->authenticate('ApiKey', ['key' => 'YourPrivateApiKey']);
```


SMS Api usage
-------------

```php
<?php

require 'vendor/autoload.php';

use Scoringline\SendinblueApi\Sendinblue;

$sendinblue = new Sendinblue();

$sendinblue->getSmsApi()->sendSms('+33600000000', 'Your name', 'The message you want to send');
```

Email Api usage
-------------
```php
<?php

require 'vendor/autoload.php';

use Scoringline\SendinblueApi\Sendinblue;

$sendinblue = new Sendinblue();

$sendinblue->getEmailApi()->sendEmail(
    ['to@example.com'=> 'to name!'],
    ['from@yahoo.com', 'from email or name!'],
    'Invitation',
    'You are invited for giving test',
    'This is the <h1>HTML</h1>'
    ['replyto@yahoo.com', 'reply to!'],
    ['cc@example.com' => 'cc name'],
    ['bcc@example.com' => 'bcc name'],
    [],
    ['Content-Type" => "text/html; charset=utf-8'],
    ['myinlineimage1.png' => 'your_png_files_base64_encoded_chunk_data']
); 
```

----------------------------------------------------------------

This library is provided to you by [Scoringline](http://en.scoringline.com), if you're searching for more efficient hiring, checkout our application !
