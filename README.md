# Simple CLI

Command-line helper functions, with almost no dependencies, to help you build console scripts quickly.

It is not intended to be a full-featured CLI framework, but rather to help you grab arguments / options that were passed to your script, display some text, and prompt for user input.

## Installation

```bash
composer require bentools/simple-cli:0.2.*
```

## Usage

```php
# php thatscript.php --file=some-file foobar
require_once __DIR__ . '/vendor/autoload.php';
use function BenTools\SimpleCli\cli;
use function BenTools\SimpleCli\getOption;
use function BenTools\SimpleCli\getArgument;
use function BenTools\SimpleCli\writeLn;
use function BenTools\SimpleCli\text;
use function BenTools\SimpleCli\ask;
use function BenTools\SimpleCli\askHidden;
use function BenTools\SimpleCli\confirm;
use function BenTools\SimpleCli\error;
use function BenTools\SimpleCli\success;

getOption('file'); // some-file
getArgument(0); // foobar
writeLn(text('Hey there!')->yellow());

$user = ask('What is your username?', $default = 'anonymous');
$password = askHidden('Enter your password');

if ('123456' === $password && confirm('Really?')) {
    error('Nope nope nope!', $exit = true);
}

success('Done!');
```

## License

MIT.
