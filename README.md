# Mailhog Testcase for Laravel

A simple testcase for using Mailhog with Laravel.

## Installation

## Usage


Instead of extending the normal TestCase in your testclasses, now extend SiegerHansma\MailhogTestcase\MailhogTestcase. The MailhogTestcase class itself extends the base Laravel TestCase so you can still use all the methods that Laravel provides. 

```
class RegisterTest extends MailhogTestCase
```

## Configuration
Mailhog running on another port? We've got you covered. Simply add a mailhogBasepath property to your test class, like so. 

```
    protected $mailhogBasepath = 'http://localhost:12345';
    
    class RegisterTest extends MailhogTestCase {

```

### API overview

Still todo. The main Testcase is already documented. 




