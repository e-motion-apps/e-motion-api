## 🛴 escooters
### Local development
```
cp .env.example .env
make init
make shell
  # inside container
  npm run dev
```
Application will be running under [localhost:3851](localhost:3851) and [http://escooters.blumilk.localhost/](http://escooters.blumilk.localhost/) in Blumilk traefik environment. If you don't have a Blumilk traefik environment set up, follow the instructions in this [repository](https://github.com/blumilksoftware/environment).


### Commands
Before run every command from below list, you must run shell:
```
make shell
```
#### Command list
Composer:
```
composer <command>
```
Run backend tests:
```
composer test
```
Lints backend files:
```
composer cs
```
Lints and fixes backend files:
```
composer csf
```
Artisan commands:
```
php artisan <command>
```
Compiles and hot-reloads frontend for development:
```
npm run dev
```
Compiles and minifies for production:
```
npm run build
```
Lints frontend files:
```
npm run lint
```
Lints and fixes frontend files:
```
npm run lintf
```

### Containers

| service  | container name               | default host port             |
|:---------|------------------------------|-------------------------------|
| app      | escooters-app-dev     | [3851](http://localhost:3851) |
| database | escooters-db-dev      | 3853                          |
| mailpit  | escooters-mailpit-dev | [8566](http://localhost:3856) |
| redis    | escooters-redis-dev   | 3852                          |
