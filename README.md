## escooters

### Local development
```
cp .env.example .env
make init
make shell
  # inside container
  npm run dev
```
Application will be running under [localhost:8561](localhost:3851) and [http://escooters.blumilk.localhost/](http://escooters.blumilk.localhost/) in Blumilk traefik environment. If you don't have a Blumilk traefik environment set up, follow the instructions in this [repository](https://github.com/blumilksoftware/environment).

#### Containers

| service  | container name               | default host port             |
|:---------|------------------------------|-------------------------------|
| app      | escooters-app-dev     | [3851](http://localhost:3851) |
| database | escooters-db-dev      | 3853                          |
| mailpit  | escooters-mailpit-dev | [8566](http://localhost:3856) |
| redis    | escooters-redis-dev   | 3852                          |
