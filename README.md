# Agnes UI

This UI allows to use the agnes capabilities efficiently. Primary goals are:

- Allow to create releases easily and with accurate descriptions 
- Give a clear picture of where is what installed
- Deploy & rollback to whole stages or specific instances
- Edit configuration files safely
- Execute tasks on environments without having to login

## Setup

Make sure the server where the tool is installed has ssh access to the environments you configured.

## Developer tools

install `symfony-cli`, `vue-cli` and `openapi-generator-cli`

start the server with `symfony serve` & the frontend with `cd js && yarn serve`.
for the API requests to work, ensure CORS is enabled for localhost (https://addons.mozilla.org/de/firefox/addon/cors-everywhere/)
