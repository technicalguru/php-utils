# php-utils
This is a collection of useful classes and functions for every day PHP life. It includes things such as:

* Handling dates and times and various appearances of it
* Searching and sorting of arrays of objects
* Logging to error_log with different levels
* Notification to users across various requests within a session
* Extracting information from the current HTTP request
* Generating random strings

These classes are no rocket science, just simple helpers that prevent from wiriting the
same code in various flavours over and over again.

# License
This project is licensed under [GNU LGPL 3.0](LICENSE.md). 

# Installation

## By Composer

```
composer install technicalguru/utils
```

## By Package Download
You can download the source code packages from [GitHub Release Page](https://github.com/technicalguru/php-utils/releases)

# Classes

## Request class
The Request class extracts many useful information from the current HTTP request. It provides:

* Header information
* GET and POST parameter values, assigning defaults if not present
* Getting the method, protocol, host and path information from the HTTP request
* Checking the elapsed time of this request

It is possible to create an object yourself, but it is recommend to use the singleton:

```
$request = \TgUtils\Request::getRequest();
```

Inspect the [source code](https://github.com/technicalguru/php-utils/blob/src/TgUtils/Request.php) to find out about the various methods available.

## Date class

The date class handles issues for localization, timezones and conversion into different formats or creation from various formats. Here are some examples:

```
use TgUtils\Date;

$timezone = 'Europe/Berlin';

// Creating instances with local timezone
$date = new Date(time(), $timezone);
$date = Date::createLocalInstance(time(), $timezone);
$date = Date::createFromMysql($mySqlTimestampString, $timezone);

// The Epoch time in seconds
$unixTimestamp = $date->toUnix();

// Converting and formatting with local timezone 
$mysqlTimstamp = $date->toMysql(TRUE);
$iso8601       = $date->toISO8601(TRUE);
$someString    = $date->format('d.m.Y H:i:s', TRUE);

// Converting to UTC timezones instances
$mysqlTimstamp = $date->toMysql();
$iso8601       = $date->toISO8601();
$someString    = $date->format('d.m.Y H:i:s');
```

Inspect the [source code](https://github.com/technicalguru/php-utils/blob/src/TgUtils/Date.php) to find out about the various methods available.

## Logging

Logging is very simple. Set your log level (if not INFO) and start logging:

```
use TgLog\Log;

// Set the log level
Log::setLogLevel(Log::ERROR);

// Simple line
Log::error('A simple error message');

// This message will not go into the log
Log::info('This message is lost.');

// Log an exception
Log::error('An expception was caught: ', $exception);

// Log an object for later debugging
Log::error('We have some problem here:', $object);
```

The Log can also help when you need to debug something:

```
use TgLog\Log;

// Get the stacktrace
$stacktrace = Log::getStackTrace(__FILE__);

// Or log it with INFO level
Log::infoStackTrace(__FILE__);
```

The `__FILE__` parameter will oppress the current file from appearing in the stacktrace. You
can easily use the function without this argument to have the current file included:

```
$stacktrace = Log::getStackTrace();
Log::infoStackTrace();
```

## User Notifications

Sometimes it is hard to display success or error messages because the message needs to
be displayed in another script, another HTTP call or even just later. The following snippet
will remember the message across multiple call within a session.

```
use TgLog\Log;
use TgLog\Success;

Log::register(new Success('Your data was saved successfully.));
```

The registartion will not only remember the message but also print the message into
the error log when it's an instance of `Debug`, `Error`, `Warning` or `Info`.

In another call or script you can retrieve your messages again to display:

```
use TgLog\Log;
use TgLog\Success;

foreach (Log::get() AS $message) {
	switch ($message->getType()) {
	case 'success':
		echo '<div class="text-success">'.$message->getMessage().'</div>';
		break;
	case 'error':
		echo '<div class="text-danger">'.$message->getMessage().'</div>';
		break;
	}
}

// Finally, clean all messages
Log::clear();
```

## Other Utils
There are some daily tasks that need to be done in applications. The `Utils` class addresses a few of them:

```
use TgUtils\Utils;

// create a random string
$myId = Utils::generateRandomString(20);

// Find an object with UID 1
$myObject = Utils::findByUid($objectList, 3);

// Find an object with a certain name
$myObject = Utils::findBy($objectList, 'name', 'John');

// Sort an array of objects by its names
Utils::sort($objectList, 'name');

// Sort an array of objects in reverse order of names
Utils::sort($objectList, 'name', TRUE);

// Sort an array of objects in reverse order of names, ignore upper/lower case
Utils::sort($objectList, 'name', TRUE, TRUE);

// Get all names from the list of objects
$allNames = Utils::extractAttributeFromList($objectList, 'name');

// Mask a sensitive string
$masked = Utils::anonymize($aPhoneNumber);
```

Inspect the [source code](https://github.com/technicalguru/php-utils/blob/src/TgUtils/Utils.php) to find more about the methods available.

# Contribution
Report a bug, request an enhancement or pull request at the [GitHub Issue Tracker](https://github.com/technicalguru/php-utils/issues).
