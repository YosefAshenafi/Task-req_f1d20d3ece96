# CampusOps

Campus operations portal: ThinkPHP 8 backend, Layui frontend, MySQL, Nginx — run entirely with Docker Compose.

## Run with Docker

**Prerequisites:** [Docker](https://docs.docker.com/get-docker/) with Compose.

From this directory:

```bash
make setup
```

Builds images, starts nginx, PHP, MySQL, and the Node helper container; installs Composer dependencies; fetches Layui for the frontend.

Then apply schema and seed data:

```bash
make migrate
make seed
```

**Useful commands**

| Command | Purpose |
|--------|---------|
| `make up` | Start containers |
| `make down` | Stop containers |
| `make restart` | Restart stack |
| `make logs` | Follow service logs |
| `make shell-php` | Shell in the PHP container |
| `make shell-mysql` | MySQL CLI as `root` |

**App URL:** [http://localhost:8080](http://localhost:8080) (nginx maps host `8080` → container `80`).

## Default credentials

**Application** (after `make seed`; all accounts use the same password)

| Username | Password | Role |
|----------|----------|------|
| `admin` | `CampusOps1` | Administrator |
| `ops_staff1`, `ops_staff2` | `CampusOps1` | Operations staff |
| `team_lead` | `CampusOps1` | Team lead |
| `reviewer` | `CampusOps1` | Reviewer |
| `user1`–`user5` | `CampusOps1` | Regular user |

**MySQL** (defaults from `docker-compose.yml`)

| User | Password |
|------|----------|
| `root` | `root_secret` |
| `campusops` | `campusops_secret` |

## Tests

With dependencies installed (`make setup` or `make install` so `backend/vendor` exists), from the repository root:

```bash
./run_tests.sh
```

Runs PHPUnit (unit, API, e2e) and writes JUnit XML and logs under `test-results/`.

---

More detail: [Design](docs/design.md) · [API](docs/api-spec.md) · [Assumptions](docs/questions.md)
