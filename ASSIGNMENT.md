# Assignment

The current part of [ROADMAP.md](ROADMAP.md), split into checkpoints you
build one at a time.

## How it works

1. Build the checkpoint until everything under **Done when** is true.
2. Tick its box and ask for a review: "check 1.2".
3. The review goes on the checkpoint's **Review** line:
   - **Passed**: go on to the next checkpoint.
   - **Revise**: the line says what doesn't meet **Done when** yet. You
     get hints, not fixes.
4. **Hints** are links and pointers for when you're stuck. Try without
   them first.
5. **Think about later** questions are optional. Park your thoughts in
   [notes/questions.md](notes/questions.md) if you like; we'll discuss
   them once the slice works.

---

## Slice 1: Catalog

**Goal:** a librarian can add a book through the API and read it back,
built in hexagonal layers from the domain outwards.

The rules for this slice:
- A book has an ISBN, a title and one or more authors.
- An ISBN is a valid ISBN-13: 13 digits, starting with 978 or 979, with a
  correct check digit. Hyphens and spaces in the input are allowed and
  ignored.
- The title can't be empty.
- Two books can't have the same ISBN.

### Checkpoint 1.0: warm up

- [ ] Done

Build:
- Start the stack with `make up` and open http://localhost:8080/api.
- Open a shell with `make sh` and run `bin/console`, `make test`.

Done when:
- You see the API docs page and `make test` runs (with no tests yet).

Review: _not submitted_

### Checkpoint 1.1: folder structure

- [ ] Done

Build:
- Delete the default folders Symfony created in `src/` (`Entity`,
  `Repository`, `ApiResource`, `Controller`).
- Create a `Catalog` folder in `src/` with one folder per hexagonal
  layer. You choose the names and how deep it goes.
- Point Doctrine and API Platform at your new structure instead of the
  deleted folders.
- Create `tests/Unit`, `tests/Integration` and `tests/Functional`.

Done when:
- `bin/console cache:clear` and `bin/console debug:container` run without
  errors.
- http://localhost:8080/api still loads.
- No configuration file mentions the deleted folders.

Hints:
- `config/services.yaml`, `config/packages/doctrine.yaml`,
  `config/packages/api_platform.yaml`
- https://api-platform.com/docs/core/getting-started/

Think about later:
- Why does each layer exist, and which layer may use which?

Review: _not submitted_

### Checkpoint 1.2: the ISBN value object

- [ ] Done

Build:
- A class in your domain layer that represents an ISBN and enforces the
  rules above.
- Unit tests in `tests/Unit` for valid ISBNs, invalid ones (wrong length,
  wrong prefix, wrong check digit, letters) and input with hyphens.

Done when:
- `make test` passes.
- Creating an invalid ISBN is impossible: it fails with your own domain
  exception, not a generic one.
- Two ISBN objects with the same digits count as equal.
- The class imports nothing from `Symfony\` or `Doctrine\`.

Hints:
- Look up how the ISBN-13 check digit is calculated (weights 1 and 3).
- https://docs.phpunit.de/en/12.0/writing-tests-for-phpunit.html, see
  data providers.

Think about later:
- Is this an entity or a value object, and what makes the difference?

Review: _not submitted_

### Checkpoint 1.3: the Book entity

- [ ] Done

Build:
- A `Book` in your domain layer with an id, an ISBN, a title and authors.
- Decide how a book gets its id (hint: think about who generates it, and
  when).
- Unit tests for creating a book and for the title and author rules.

Done when:
- `make test` passes.
- You can't create a book in an invalid state.
- Still no `Symfony\` or `Doctrine\` imports in the domain.

Hints:
- `composer require symfony/uid` gives you UUIDs. Is it fine to use that
  in the domain? Decide, and be ready to defend it.

Think about later:
- Should authors be plain strings or their own concept?

Review: _not submitted_

### Checkpoint 1.4: repository port and the "add book" use case

- [ ] Done

Build:
- A repository interface (a port) for books, in the layer you think it
  belongs to.
- An in-memory implementation of it, for tests.
- An application-layer use case that adds a book to the catalog, and
  rejects a duplicate ISBN.
- Unit tests for the use case that use the in-memory repository.

Done when:
- `make test` passes, without a database or the Symfony kernel.
- Adding a book with an ISBN that already exists fails with a domain or
  application exception.

Hints:
- Search for "command handler" and "use case interactor".

Think about later:
- Where does the in-memory repository live: `src/` or `tests/`? Why?

Review: _not submitted_

### Checkpoint 1.5: Doctrine adapter

- [ ] Done

Build:
- A Doctrine implementation of your repository port, in the
  infrastructure layer.
- Doctrine mapping for `Book` that keeps the domain class free of Doctrine
  attributes.
- A way to store your ISBN value object in a column.
- A migration, applied with `make db`.
- An integration test in `tests/Integration` that saves a book and loads
  it again from `library_test`.

Done when:
- `make db` creates the `book` table.
- `bin/console doctrine:schema:validate` reports no errors.
- `make test` passes, including the integration test.
- The domain still has no Doctrine imports.

Hints:
- https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/xml-mapping.html
- Doctrine embeddables, or custom DBAL types.
- https://symfony.com/doc/current/testing.html, see "Integration Tests".
- Run `bin/console doctrine:database:create --env=test` and the schema
  for the test database yourself first.

Review: _not submitted_

### Checkpoint 1.6: API Platform adapter

- [ ] Done

Build:
- `POST /api/books` that adds a book through your use case.
- `GET /api/books/{id}` that returns it.
- The API works with its own classes (DTOs), not with your domain `Book`.
- Errors map to sensible HTTP codes: invalid input → 422, duplicate ISBN
  → 409, unknown id → 404.
- Functional tests in `tests/Functional` for each of those cases.

Done when:
- These work from your terminal:
  ```sh
  curl -X POST http://localhost:8080/api/books \
    -H 'Content-Type: application/ld+json' \
    -d '{"isbn":"978-0-13-235088-4","title":"Clean Code","authors":["Robert C. Martin"]}'
  curl http://localhost:8080/api/books/<id from the response>
  ```
- Posting the same ISBN twice gives a 409.
- `make test` passes.

Hints:
- https://api-platform.com/docs/core/dto/
- https://api-platform.com/docs/core/state-processors/
- https://api-platform.com/docs/core/state-providers/
- https://api-platform.com/docs/core/errors/
- https://api-platform.com/docs/core/testing/

Think about later:
- If you replaced API Platform with a CLI command, which files would you
  have to change?

Review: _not submitted_

---

## Completed

_Nothing yet._
