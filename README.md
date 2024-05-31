# Wiki

Simple database-less Wiki app built using [Nette Framework](https://nette.org/)

## Features
* Based on [Nette 3](https://doc.nette.org/en/3.x)
* File based storage, no need for database
* Write Wiki pages using [Markdown](https://commonmark.org/help/)
* [CommonMark](https://commonmark.org/) support with table extension

## Installation

```bash
composer create-project sunfoxcz/foxywiki
```

## Development

Run:
```bash
docker-compose up
```

Add to `/etc/hosts`:
```bash
127.0.0.1 wiki.test
```

Point your browser to `https://wiki.test/`. Certificate is self-signed,
so you need to accept security warning.
