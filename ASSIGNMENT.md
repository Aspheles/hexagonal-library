# Assignment

This file holds the current milestone from [ROADMAP.md](ROADMAP.md),
split into checkpoints.

## How it works

1. Do the tasks of one checkpoint.
2. Write your answers in the notes file for the milestone, under the
   matching question number (for example `Q0.1.2`).
3. Tick the checkpoint's box below and ask for a review: "check 0.1".
4. The review goes into the checkpoint's **Review** line:
   - **Passed**: go on to the next checkpoint.
   - **Revise**: there are follow-up questions for you to answer in your
     notes. Answer them, then ask for a review again.
5. When every checkpoint is passed, the milestone moves to **Completed**
   at the bottom and the next one takes its place.

Write answers in your own words. "I don't know, I tried X and saw Y" is a
valid answer and a better one than a guess.

---

## Milestone 0: get to know the stack

**Goal:** understand how the empty project works before changing it.
**Notes file:** [notes/milestone-0.md](notes/milestone-0.md)

### Checkpoint 0.1: start the project

- [ ] Done

Tasks:
- Run `make help` and read the list of commands.
- Run `make up`, then `docker compose ps`.
- Open http://localhost:8080/api.
- Run `make sh` to open a shell in the PHP container, then run
  `bin/console` with no arguments.

Questions:
- **Q0.1.1** What does each of the three containers do?
- **Q0.1.2** Which container receives your browser's request first, and
  which one runs the PHP?
- **Q0.1.3** In `compose.yaml` and `.env`, the PHP container reaches MySQL
  through the hostname `database`. Why can't it use `localhost`?

Review: _not submitted_

### Checkpoint 0.2: follow a request

- [ ] Done

Tasks:
- Read, in this order: `docker/nginx/default.conf`, `public/index.php`,
  `src/Kernel.php`.
- Inside the container, run `bin/console debug:router`.

Questions:
- **Q0.2.1** How does nginx decide whether a URL goes to PHP?
- **Q0.2.2** What does `public/index.php` do?
- **Q0.2.3** Which routes exist although you wrote no controllers, and
  where do they come from?

Review: _not submitted_

### Checkpoint 0.3: how Symfony discovers classes

- [ ] Done

Tasks:
- Read `config/services.yaml`, including the comments.
- Read the "Service Container" page of the Symfony documentation.
- Experiment: create a small, empty class anywhere in `src/`, run
  `bin/console debug:container` and search for it. Then delete the class.

Questions:
- **Q0.3.1** Which folder does Symfony scan to register services?
- **Q0.3.2** What do `autowire` and `autoconfigure` mean?
- **Q0.3.3** Did your empty class show up as a service? Explain why or why
  not.

Review: _not submitted_

### Checkpoint 0.4: where Doctrine and API Platform look

- [ ] Done

Tasks:
- Read `config/packages/doctrine.yaml` and
  `config/packages/api_platform.yaml`.
- Find `DATABASE_URL` in `.env`.
- Inside the container, run `bin/console dbal:run-sql "SELECT VERSION()"`.

Questions:
- **Q0.4.1** In which folder does Doctrine look for entities, and in which
  format does it expect the mapping?
- **Q0.4.2** In which folder does API Platform look for resources?
- **Q0.4.3** What MySQL version did the query return?
- **Q0.4.4** Why is `.env` committed to git while `.env.local` is not?

Review: _not submitted_

### Checkpoint 0.5: reflect

- [ ] Done

Tasks:
- Re-read "Rules for the codebase" in [ROADMAP.md](ROADMAP.md).
- Draw the layers of a hexagonal application and the direction of the
  dependencies. A photo of a paper sketch in `notes/` is fine, or describe
  it in text.

Questions:
- **Q0.5.1** Describe your sketch: which layers, and which depends on
  which?
- **Q0.5.2** For each folder Symfony created in `src/`, does it fit your
  sketch, and if so in which layer? Give your reasoning.
- **Q0.5.3** Name at least one way Doctrine's default configuration
  conflicts with "the domain does not know Doctrine exists". Only the
  problem, no solution yet.

Review: _not submitted_

---

## Completed

_Nothing yet._
