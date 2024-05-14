# E-motion API
![logo.png](public/icons/logo.svg)

Project developed to allow checking available options of urban mobility. 

This is API part of the project,
which is responsible for fetching data from different providers and returning it in unified format.

API is consumed by applications in those repositories:
- [escooters-web](https://github.com/blumilksoftware/e-motion-web)
- [escooters-mobile](https://github.com/blumilksoftware/e-motion-mobile)
- [escooters-desktop](https://github.com/blumilksoftware/e-motion-desktop)

#### API documentation may be found in 
[Swagger](api.json)

### Available providers

1. Baqme
1. Beam
1. Beryl
1. BinBin
1. Bird
1. BitMobility
1. Bolt
1. Docomo
1. Dott
1. Felyx
1. GoSharing
1. Hop
1. Hulaj
1. Lime
1. Link
1. Neuron
1. Quick
1. Ryde
1. Sixt
1. Spin
1. Tier
1. Urent
1. Veo
1. Voi
1. WheeMove
1. Wind
1. Zwings

### Local development
```
cp .env.example .env
make init
make shell
  # inside container
  npm run dev
```
Application will be running under [localhost:3851](http://localhost:3851) and [http://escooters.blumilk.localhost/](http://escooters.blumilk.localhost/) in Blumilk traefik environment. If you don't have a Blumilk traefik environment set up, follow the instructions in this [repository](https://github.com/blumilksoftware/environment).


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
Runs a queue that processes importers' jobs:
```
php artisan queue:work
```

### Project is no longer maintaining frontend part, and will be moving to API only.
If you want to run frontend, you can use following commands:

Npm:

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

| service  | container name        | default host port             |
|:---------|-----------------------|-------------------------------|
| app      | escooters-app-dev     | [3851](http://localhost:3851) |
| database | escooters-db-dev      | 3853                          |
| mailpit  | escooters-mailpit-dev | [8566](http://localhost:3856) |
| redis    | escooters-redis-dev   | 3852                          |
