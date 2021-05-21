## Simple Address Book

This is a simple address book created using Laravel framework.  
Dev setup was done on Docker using [Bitnami Laravel Development Container](https://hub.docker.com/r/bitnami/laravel/)

### Features

- User registration, login, logout
- Only own address book entries are viewable to the user
- Possible to share entry with another registered user
- CRUD for own address book entry


### Run

````
$ git clone https://github.com/sakaljurgis/Address_Book.git
$ cd Address_Book
$ cp .env.example .env
$ docker-compose up
$ docker-compose exec myapp php artisan key:generate
````
App available in http://localhost:3000 

## License

Currently no licence
