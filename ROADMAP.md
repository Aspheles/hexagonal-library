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

## Slices

The project is built in vertical slices: each one goes from the domain
all the way out to a working API endpoint, with tests. The current slice
is broken into checkpoints in [ASSIGNMENT.md](ASSIGNMENT.md).

1. **Catalog.** Folder structure, value objects and entities, a
   repository port with Doctrine and in-memory adapters, a first use case,
   and API Platform with DTOs, providers and processors.
2. **Membership and lending.** Members, borrowing and returning copies,
   the 5-loan and EUR 10 rules, extensions, a `Clock` port, money as a
   value object, and the read side (queries).
3. **Messaging and domain events.** Symfony Messenger as a command and
   query bus, events such as `CopyReturned` and `LoanBecameOverdue`,
   asynchronous handlers on the Doctrine transport, and reservations with
   a pickup deadline.
4. **The outside world.** A `Notifier` port with a Symfony Mailer adapter
   (Mailpit in Docker), an `IsbnLookup` port backed by the Open Library
   API, and a console command that marks overdue loans.
5. **Security and quality.** Members and librarians with Symfony Security,
   PHPStan, architecture tests with deptrac, and step-debugging with
   Xdebug (`XDEBUG_MODE=debug make up`).
