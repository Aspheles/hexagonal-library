# Library Lending: learning roadmap

## The project

An API for a small public library network. Members borrow book copies,
return them late, pay fines, and queue up reservations for books that are
all lent out.

This domain was chosen because its rules are easy to understand but strict
enough to belong in a real domain model, not in controllers or SQL:

- A **book** (title, ISBN, authors) has one or more physical **copies**.
- A **member** may hold at most 5 loans at once, and none while they owe
  more than EUR 10 in fines.
- A **loan** lasts 21 days and can be extended twice, unless someone has
  reserved the book.
- Returning a copy late creates a **fine** of EUR 0.25 per day, capped at
  EUR 10 per loan.
- When a reserved book comes back, the first member in the
  **reservation** queue gets 3 days to pick it up, then it goes to the
  next.
- Members are notified (by email or, later, something else) when a
  reservation is ready or a loan is overdue.

## Rules for the codebase

1. The domain does not know Symfony, Doctrine or API Platform exist.
2. Dependencies point inward: infrastructure → application → domain.
3. Every way in (HTTP, CLI, messages) is a primary adapter. Every way out
   (database, mail, clock, ISBN lookup) is a port with a secondary adapter.
4. Tests live in `tests/`, mirroring `src/` (`tests/Unit`,
   `tests/Integration`, `tests/Functional`), never next to source files.

## Milestones

Each milestone ends with something you can run or test. Work through the
questions before writing code; bring your answers to our sessions.

### 0. Get to know the stack
- `make up`, open http://localhost:8080/api, run `make help`.
- Where does Symfony decide which classes are services? Where does
  Doctrine look for entities, and API Platform for resources?
- Question: right now `src/` has `Entity`, `Repository`, `ApiResource`
  and `Controller`. Which of those would survive in a hexagonal layout,
  and in which layer?

### 1. Folder structure and boundaries
- Design the `src/` tree: bounded contexts (Catalog, Lending, Membership?)
  and the layers inside each.
- How would you *prove* the domain never imports `Symfony\` or
  `Doctrine\`? (Look up `deptrac` or `phpat`.)

### 2. Pure domain: Catalog
- Model `Book`, `Copy` and `Isbn`. Which are entities and which are value
  objects? What makes the difference?
- What should happen when someone builds an `Isbn` from `"abc"`?
- Unit-test it all with plain PHPUnit: no kernel, no database.

### 3. Persistence: Doctrine as an adapter
- Define a `BookRepository` port in the domain. Who implements it?
- Attributes on domain classes or XML mapping in infrastructure? What does
  each choice cost you?
- Map value objects with Doctrine embeddables or custom types.
- Create migrations; write an integration test against `library_test`.

### 4. Use cases: the application layer
- Commands and handlers (`AddBookToCatalog`, `RegisterMember`).
- Queries and read models: should a query go through the repository and
  the aggregate, or straight to the database?
- Introduce Symfony Messenger as a command/query bus. Is the bus part of
  your application layer or infrastructure?

### 5. API Platform as a primary adapter
- Expose resources through DTOs, not domain entities.
- Write State Providers (reads) and State Processors (writes) that call
  your use cases.
- How do domain exceptions become `404`, `409` or `422` responses?
- Functional tests with API Platform's `ApiTestCase`.

### 6. The Lending aggregate
- `BorrowCopy`, `ReturnCopy`, `ExtendLoan`. Where do the 5-loan and
  EUR 10 rules live, and which aggregate owns them?
- Time is a dependency: design a `Clock` port so "21 days later" is
  testable.
- Money: why not a `float`?

### 7. Domain events
- `CopyReturned`, `LoanBecameOverdue`, `ReservationReady`.
- Who records events, who dispatches them, and when (before or after the
  transaction commits)?
- Handle them asynchronously with Messenger (the `doctrine` transport works
  with MySQL, no extra container).

### 8. Secondary adapters to the outside world
- `Notifier` port with a Symfony Mailer adapter (add Mailpit to Docker).
- `IsbnLookup` port backed by the Open Library API, plus an in-memory fake
  for tests.
- A console command that marks overdue loans (another primary adapter).

### 9. Security and polish
- Members vs. librarians: Symfony Security in the adapter, authorization
  rules in the application layer?
- Static analysis with PHPStan, architecture tests in CI, Xdebug
  step-debugging (`XDEBUG_MODE=debug make up`).
