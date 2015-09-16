# Email Watcher
Trig an action on a new email (protcol pop3, imap)

##Installation 

Via composer

    composer require k3roulas/email-watcher

Beware that there is a dependancy to the package tedivm/fetch https://github.com/tedious/Fetch

You need to install the module php5-imap.

On ubuntu :

    apt-get install php5-imap
    php5enmod imap


##Usage

After installing the library, you should test it with the builtin NewEmailWatcherDump.

Once you have validated that your application communicate with your email server, implement the interface NewEmailWatcherInterface and use your own class instead of NewEmailWatcherDump. See example below.

## Gmail
Using a gmail account, you will have to enable pop3 or imap. Make an attempt. Wait for an security alert that you will receive on your email. Follow the instruction. You will may have to set the option "app less secure" : https://www.google.com/settings/security/lesssecureapps

##Example


```php
// example with a gmail account
$port = 993;
$email = 'bob@sinclar.dance';
$password = 'IAmNotDavidGuetta';
$filename = './lastuid.json';
$server = 'imap.gmail.com';

// Nominal usage
$newEmailWatcher = new \K3roulas\EmailWatcher\NewEmailWatcherDump();

$emailWatcher = new \K3roulas\EmailWatcher\Watcher();
$emailWatcher->setServer($server)
    ->setPort($port)
    ->setEmail($email)
    ->setPassword($password)
    ->setFilename($filename)
    ->setProtocol('imap')
    ->setNewEmailWatcher($newEmailWatcher)
    ->init();

$emailWatcher->process();


// Use another persitence service : Create your own class that implement LastUidPersistInterface
$lastUidPersist = new MyPersitenceService();
$newEmailWatcher = new \K3roulas\EmailWatcher\NewEmailWatcherDump();

$emailWatcher = new \K3roulas\EmailWatcher\Watcher();
$emailWatcher->setServer($server)
    ->setPort($port)
    ->setEmail($email)
    ->setPassword($password)
    ->setProtocol('imap')
    ->setNewEmailWatcher($newEmailWatcher)
    ->setLastUidPersist($lastUidPersist)
    ->init();

$emailWatcher->process();



// You need more complicated options to access to the server @see Fetch/Server package

$fetchServer = new \Fetch\Server($server, $port);
// For example if you use GSSAPI or  NTLM
$fetchServer->setAuthentication($email, $password, false);

$newEmailWatcher = new \K3roulas\EmailWatcher\NewEmailWatcherDump();

$emailWatcher = new \K3roulas\EmailWatcher\Watcher();
$emailWatcher->setFetchServer($fetchServer)
    ->setNewEmailWatcher($newEmailWatcher)
    ->setFilename($filename)
    ->init();

$emailWatcher->process();
```
