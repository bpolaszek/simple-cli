<?php

namespace BenTools\SimpleCli;

use Colors\Color;

final class SimpleCli implements \Countable
{
    private $command;
    private $options = [];
    private $arguments = [];
    private $color;
    private $aliases = [];

    public function __construct(array $args = null, Color $color = null)
    {
        [$this->command, $this->options, $this->arguments] = self::parse($args ?? (array) ($_SERVER['argv'] ?? []));
        $this->color = $color ?? new Color();
    }

    public function alias(string $shortOptionName, string $targetOptionName): void
    {
        $this->aliases[$shortOptionName] = &$this->options[$targetOptionName];
    }

    public function text(string $string): Color
    {
        return ($this->color)($string);
    }

    public function success(string $message, bool $exit = false): void
    {
        $this->newLine();
        $this->write($this->text('[OK] '.$message)->white()->bold()->highlight('green'));
        $this->newLine();

        if ($exit) {
            exit(0);
        }
    }

    public function error(string $message, bool $exit = false): void
    {
        $this->newLine();
        $this->write($this->text('[Error] '.$message)->white()->bold()->highlight('red'));
        $this->newLine();

        if ($exit) {
            exit(1);
        }
    }

    public function warning(string $message): void
    {
        $this->newLine();
        $this->write($this->text('[Warning] '.$message)->yellow()->bold());
        $this->newLine();
    }

    public function write(string $text): void
    {
        echo $text;
    }

    public function writeLn(string $text): void
    {
        echo \PHP_EOL.$text;
    }

    public function newLine(int $nb = 1): void
    {
        while ($nb > 0) {
            echo PHP_EOL;
            $nb--;
        }
    }

    public function getOption($key, $default = null)
    {
        return $this->aliases[$key] ?? $this->options[$key] ?? $default;
    }

    public function getArgument($key, $default = null)
    {
        return $this->arguments[$key] ?? $default;
    }

    public function getCommand(): string
    {
        return $this->command ?? '';
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function ask(string $question, $default = null)
    {
        $this->writeLn(\rtrim($question).' ');
        $value = \trim(\fgets(STDIN));

        return '' === $value ? $default : $value;
    }

    public function confirm(string $question, $default = 'Y')
    {
        $yes = ['yes', 'on', 'y','YES', 'ON', 'Y', 1, '1', true];
        $no = ['no', 'off', 'n','NO', 'OFF', 'N', 0, '0', false];

        if (!\in_array($default, \array_merge($yes, $no), true)) {
            throw new \RuntimeException('Invalid default value');
        }

        $available = ['y', 'n'];
        if (\in_array($default, $yes, true)) {
            $available[0] = 'Y';
        } else {
            $available[1] = 'N';
        }

        $this->writeLn(\rtrim($question).' '.$this->text(\vsprintf('[%s/%s]', $available))->yellow());
        $value = \trim(\fgets(STDIN));

        if ('' === $value) {
            return \in_array($default, \array_merge($yes), true);
        }

        if (!\in_array($value, \array_merge($yes, $no), true)) {
            $this->error('Invalid answer');

            return $this->confirm($question, $default);
        }

        return \in_array($value, $yes, true);
    }

    public function askHidden(string $question, $default = null)
    {
        $this->writeLn(\rtrim($question).' ');
        \system('stty -echo');
        $value = \trim(\fgets(STDIN));
        \system('stty echo');

        return '' === $value ? $default : $value;
    }

    private static function parse(array $input): array
    {
        $command = \array_shift($input);
        $options = [];
        $arguments = [];
        foreach ($input as $value) {
            if (0 === \strpos($value, '--')) {
                $pos = \strpos($value, '=');
                if ($pos !== false) {
                    $options[\substr($value, 2, $pos - 2)] = \substr($value, $pos + 1);
                } else {
                    $key = \substr($value, 2);
                    if (!isset($options[$key])) {
                        $options[$key] = true;
                    }
                }
            } elseif (0 === \strpos($value, '-')) {
                if ('=' === \substr($value, 2, 1)) {
                    $options[\substr($value, 1, 1)] = \substr($value, 3);
                } else {
                    foreach (\str_split(\substr($value, 1)) as $key) {
                        if (!isset($options[$key])) {
                            $options[$key] = true;
                        }
                    }
                }
            } else {
                $arguments[] = $value;
            }
        }

        return [$command, $options, $arguments];
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return \count($this->arguments) + \count($this->options);
    }
}
