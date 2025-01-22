# Link Shortener

## Brief

Create a URL shortening service where you enter a URL such as https://www.thisisalongdomain.com/with/some/parameters?and=here_too and it returns a short URL such as http://short.est/GeAi9K.
Tasks:

Two endpoints are required:

- /encode - Encodes a URL to a shortened URL
- /decode - Decodes a shortened URL to its original URL

Both endpoints should return JSON. There is no restriction on how your encode/decode algorithm should work. You just need to make sure that a URL can be encoded to a short URL and the short URL can be decoded to the original URL.

You do not need to persist short URLs if you don't need to you can keep them in memory. Provide detailed instructions on how to run your assignment in a separate markdown file or readme.
Cover all functionality with tests.

## Installation

### MacOS / Linux

Requirements: 

- Git
- Docker
- Docker compose plugin
- Make

Clone the repo from Github

    git clone https://github.com/AaronKaa/link-shorten.git

Enter the dir

    cd link-shorten

Run the installer

    make install

If you have all of the requirements, the solution will install and bring up the solution and provided port 80 is available (you may have other services running) you can access the solution at *http://localhost* .

You can pull down the solution with `make down` and back p with `make up`.

## Solution 

### Encoding / Decoding

I chose not to technically encode or decode the links and they are instead converted and then retrieved, im not sure if that is what the brief had in mind. The reason for this is that if I encode the link (perhaps using base64) id still have a long link: 

Encoding the link :

    https://example.com

Would produce the base64 :

    aHR0cHM6Ly9leGFtcGxlLmNvbQ==

So the final link would be :

    https://short.est/aHR0cHM6Ly9leGFtcGxlLmNvbQ==

This isn't too bad but encoding the link :

    https://arstechnica.com/gadgets/2025/01/ios-18-3-macos-15-3-updates-switch-to-enabling-apple-intelligence-by-default/

Would produce the link :

    https://short.est/aHR0cHM6Ly9hcnN0ZWNobmljYS5jb20vZ2FkZ2V0cy8yMDI1LzAxL2lvcy0xOC0zLW1hY29zLTE1LTMtdXBkYXRlcy1zd2l0Y2gtdG8tZW5hYmxpbmctYXBwbGUtaW50ZWxsaWdlbmNlLWJ5LWRlZmF1bHQv

Hardly a short link...


### Persistence 

The brief mentioned the possibility of not using persistence but due to the above mentioned interpretation of decoding, not using persistece would be difficult; So I chose Redis initially. 

As a fast data store (and 'in-memory' as a nod to the brief) I eventually also added connectors for both sqlite (which is tested) and mysql (given my solution it would be trivial to add any supported db) and this also lets me display my grasp of the laravel service container.

### Features

#### Config 

There are a couple of config vars in the .env : 

    SHORTNER_URL=http://short.est
    SHORTNER_URL_LENGTH=7
    SHORTNER_STORAGE=redis

These do what youd expect ie. set the base url of the shortening service, change the length of the url slug that is returned from the service and lastly the storage var - this lets the admin of the service change how the service is persisted. 

#### Usage

The service operates as in the brief:

Visit :

    http://localhost/encode?url=http://example.com

This will return a link such as :

    {
      "link":"http://short.est/zPQludk"
    }

Using this link, visit: 

    http://localhost/decode?url=http://short.est/zPQludk

And this will return:

    {
      "link":"https://example.com"
    }

Even if these are run in the browser you'll see if you check headers that they are :

    Content-Type: application/json

(As per the brief)

#### Catching unsupoorted behavior

Visiting any of the links without providing a url or without providing a valid url returns an error. Laravel FormRequests were deployed to validate incoming requests :

Visit : 

    http://localhost/decode or http://localhost/encode

And the response will be :

    {
      "errors": {
        "url":["The url field is required."]
      }
    }

And an invalid URL such as :

    http://localhost/decode?url=dfnvdifuvn or http://localhost/encode?url=dfnvdifuvn

The reponse would return :

    {
      "errors": {
        "url":["The url field must be a valid URL."]
      }
    }

Both circumstances will return with status code: 422: Unprocessable Content

Trying to resolve a link that doesnt exist returns a status 404 with the following response :

    {
      "exception":"UrlNotFoundException",
      "msg":"Shortened URL not found.",
       "status":404
    }


## Testing

Tests can be foud in the standard laravel tests folder */tests* and in this case all of my tests are feature tests (*/tests/Feature*).

As long as you are in the same folder as the Makefile and docker is still running :

    make test

This should run all tests and if all goes well successfully.

The tests are duplicated with one set of tests running for Redis and the other for SQLite.


## Areas of note

#### Bindings 

I created a service provider in Providers that uses contextua binding to switch the backend store.

#### Redis mocking

I added a seperate package and then the associated implementation in the app/Testing folder that lets us mock redis (you can run the tests with the redis container stopped). 

#### Validation 

FormRequests (app/Http/Requests) have been used to provide validation on our routes.
