# Simple CLI

A lightweight CLI helper, with almost no dependencies, to help you build console scripts quickly.

It is not intended to be a full-featured CLI framework, but rather to help you grab arguments / options that were passed to your script, display some text, and prompt for user input.

## Installation

```bash
composer require bentools/simple-cli:dev-master
```

## Usage

```php
# php thatscript.php --file=some-file foobar
require_once __DIR__ . '/vendor/autoload.php';
use function BenTools\SimpleCli\cli;

cli()->getOption('file'); // some-file
cli()->getArgument(0); // foobar
cli()->writeLn(cli()->text('Hey there!')->yellow());

$user = cli()->ask('What is your username?', $default = 'anonymous');
$password = cli()->askHidden('Enter your password');

if ('123456' === $password && cli()->confirm('Really?')) {
    cli()->error('Nope nope nope!', $exit = true);
}

cli()->success('Done!');
```

## License

MIT.
