1. SETUP
  - Clone the code
  - Make sure the public/ dir is accesible by the web server
  - Make sure the public/progress dir is writable
  - Make sure the storage/ dir and it's subdirs are writable
  - Create MongoDB user
      db.createUser({
        user: "import_csv",
        pwd: "secret",
        roles: [
          { role: "readWrite", db: "import_csv" }
        ]
      })
  - Update config/database.php and change the MongoDB database, username and passsword information - lines 82-87

2. RUNNING
  - Open http://<server>/<install_location> in browser
  - Press the Import button to import the csv into the DB and wait till done
  - Press the Charts button to view the charts
  - To run tests run "vendor/bin/phpunit" in the install location