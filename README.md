## SomethingDigital AdminNotify 2

A module to alert an email address on a successful/failed login attempt.
Alerting is done once for each user per IP address. An email is sent to the
user's email address on the first successful/ failed login attempt from each IP.

### Usage

Additionally, you can set an email to cc notifications for login activity in
`app/etc/env.php`
```php
...
  'sd_adminnotify' =>
  array (
    'emails' => 'test@testers.com;more@testers.com',
  ),
...
```
Multiple email addresses should be separated by semi-colons.

### TODO

- add detail to notification email. see sdinteractive/SomethingDigital_AdminNotify#5
- notify email addr / password change?
- notify admin user create?
- notify config head/foot html change?
- notify block/page html change with script?
