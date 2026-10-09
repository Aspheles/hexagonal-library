# Library Lending API

A Symfony 8 + API Platform + Doctrine project built with hexagonal
architecture, as a learning project. See [ROADMAP.md](ROADMAP.md) for the
domain and milestones.

## Requirements

Docker with Compose and `make`. PHP and Composer run inside the container.

## Getting started

```sh
make up        # build and start PHP-FPM, nginx and MySQL
make install   # install Composer dependencies
make db        # create the database and run migrations
```

| What            | Where                                   |
| --------------- | --------------------------------------- |
| API docs        | http://localhost:8080/api               |
| MySQL from host | `127.0.0.1:3307`, user `app`, pass `app`, db `library` |
| Test database   | `library_test` (created with `--env=test`) |

Run `make help` for all commands. Use `make sh` to get a shell in the PHP
container, for example to run `bin/console` or `composer require`.

Xdebug is installed but off. Start the stack with
`XDEBUG_MODE=debug make up` and add a PhpStorm server named `hexagonal`
that maps the project root to `/app`.
