# 🍎🥕 Fruits and Vegetables

## 🎯 Goal
We want to build a service which will take a `request.json` and:
* Process the file and create two separate collections for `Fruits` and `Vegetables`
* Each collection has methods like `add()`, `remove()`, `list()`;
* Units have to be stored as grams;
* Store the collections in a storage engine of your choice. (e.g. Database, In-memory)
* Provide an API endpoint to query the collections. As a bonus, this endpoint can accept filters to be applied to the returning collection.
* Provide another API endpoint to add new items to the collections (i.e., your storage engine).
* As a bonus you might:
  * consider giving option to decide which units are returned (kilograms/grams);
  * how to implement `search()` method collections;
  * use latest version of Symfony's to embbed your logic 

### ✔️ How can I check if my code is working?
You have two ways of moving on:
* You call the Service from PHPUnit test like it's done in dummy test (just run `bin/phpunit` from the console)

or

* You create a Controller which will be calling the service with a json payload

## 💡 Hints before you start working on it
* Keep KISS, DRY, YAGNI, SOLID principles in mind
* Timebox your work - we expect that you would spend between 3 and 4 hours.
* Your code should be tested

## When you are finished
* Please upload your code to a public git repository (i.e. GitHub, Gitlab)

## 🐳 Docker image
Optional. Just here if you want to run it isolated.

### 📥 Pulling image
```bash
docker pull tturkowski/fruits-and-vegetables
```

### 🧱 Building image
```bash
docker build -t tturkowski/fruits-and-vegetables -f docker/Dockerfile .
```

### 🏃‍♂️ Running container
```bash
docker run -it -w/app -v$(pwd):/app tturkowski/fruits-and-vegetables sh 
```

### 🛂 Running tests
```bash
docker run -it -w/app -v$(pwd):/app tturkowski/fruits-and-vegetables bin/phpunit
```

### ⌨️ Run development server
```bash
docker run -it -w/app -v$(pwd):/app -p8080:8080 tturkowski/fruits-and-vegetables php -S 0.0.0.0:8080 -t /app/public
# Open http://127.0.0.1:8080 in your browser
```

## Update
the work is timeboxed so there are a lot of things that are still missing :
* Different kind of validations
* Database check if fruits and vegetables exist before adding them in order to sum quantities ...
* Unit tests coverage
* More code refactoring (especially in AbstractService and may be collections)
* In order to build collections, the service consumes the local file request.json. The API route may change into POST and accept the json from the body
* Use sprintf when creating messages rather than concatenating strings
* add composer install execution into dockerfile
* ...

The DB used is SQLite so it doesn't need additional Docker config.
I used Nginx in order to make the app reachable without ports in the URL

## Docker config with additional steps
### 📥 Pulling image
```bash
docker pull tturkowski/fruits-and-vegetables
```

### 🧱 Building image
```bash
docker build -t tturkowski/fruits-and-vegetables -f docker/Dockerfile .
```

### 🏃‍♂️ Running containers
```bash
docker-compose -f ./docker/compose.yaml up 
```

### ⚙️ Starting app
#### Enter container
```bash
docker exec -it docker-app-1 sh
```
#### Install dependencies
```bash
composer install
```
or
```bash
composer update
```
#### Setup database 
```bash
bin/console doctrine:migration:migrate
```

#### setup hosts file
```bash
127.0.0.1 http://fruits_vegetables.local/
```

### 🖥️ API routes
#### Import file
```http request
GET http://fruits_vegetables.local/api/import_json
```
#### Get fruits collection
```http request
GET http://fruits_vegetables.local/api/fruits
```
#### Get vegetables collection
```http request
GET http://fruits_vegetables.local/api/vegetables
```
#### Get fruits collection with filters (filters not mandatory)
```http request
GET http://fruits_vegetables.local/api/fruits?quantity=10000&name=Kiwi&unit=kg
```
#### Add fruit into fruits collection
```http request
PUT http://fruits_vegetables.local/api/fruits
```
```json
{
    "name": "Watermelon",
    "quantity": 10000,
    "unit": "kg"
}
```

#### Add vegetable into vegetables collection
```http request
PUT http://fruits_vegetables.local/api/fruits
```
```json
{
  "name": "Potato",
  "quantity": 50000,
  "unit": "g"
}
```
