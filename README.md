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
use Scoringline\SendinblueApi\Model\Email;
use Symfony\Component\HttpFoundation\File\File;

$sendinblue = new Sendinblue();

$email = new Email($sendinblue);

// Send basic email
$email
    ->setTo(['to@example.com' => 'to name!'])
    ->setFrom(['to@example.com', 'to name!'])
    ->setSubject('Invitation')
    ->setText('You are invited for giving test');
    ->setHtml('This is the <h1>HTML</h1>')
;
    
$sendinblue->sendEmail($email);      

// Send advance email with attachment
$email = new Email($sendinblue);
$file = new File('fixtures/test.doc');
$email
    ->setTo(['to@example.com' => 'to name!'])
    ->setFrom(['to@example.com', 'to name!'])
    ->setSubject('Invitation')
    ->setText('You are invited for giving test');
    ->setHtml('This is the <h1>HTML</h1>')
    ->setAttachments(['fixtures/logo.png', $file])
    ->setInlineImages(['fixtures/logo.png', 'fixtures/logo_one.png'])
;
    
$sendinblue->sendEmail($email, 'advance');        
    
// Send advance email with cc, bcc etc
$email = new Email($sendinblue);
$email
    ->setTo(['to@example.com' => 'to name!'])
    ->setFrom(['to@example.com', 'to name!'])
    ->setSubject('Invitation')
    ->setText('You are invited for giving test');
    ->setHtml('This is the <h1>HTML</h1>')
    ->setReplyTo(['replyto@example.com', 'replyto name'])
    ->setCc(['cc@example.com' => 'cc name'])
    ->setBcc(['bcc@example.com' => 'Bcc name'])
;

$sendinblue->sendEmail($email, 'advance'); 

```
----------------------------------------------------------------

This library is provided to you by [Scoringline](http://en.scoringline.com), if you're searching for more efficient hiring, checkout our application !
