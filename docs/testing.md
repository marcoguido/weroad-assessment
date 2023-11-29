# :test_tube: Testing project APIs

## Functional tests

In order to test the set of APIs being implemented, you need a tool capable of performing HTTP requests. Examples of
such tools can be `curl` (if you are comfortable with it and with CLI interfaces), Postman or Insomnia.

A list of available APIs can be found via [Swagger](http://localhost/swagger), which loads the `public/openapi.json`
document.

### Testing public APIs

In order to test the public APIs, open your testing tool of choice and create a new request configured as follows:

- Base url: `http://localhost/`
- Path: use the API-specific path (public ones all starts with `api/public/`)
- Headers:
  - `Accept` with the value of `application/json`

Optional query parameters can always be found inside Swagger.

### Testing private APIs

All private APIs are subjected to authentication and require a token to be invoked.

#### Authentication API

A call to the login API can be made with the following configuration:
- Endpoint: `http://localhost/api/login`
- Method: `POST`
- Headers:
  - `Accept` with the value of `application/json`
  - `Content-Type` with the value of `application/json`
- Payload
    ```json
    {
       "email": "foo@bar.xyz",
       "password": "SomePassword"
    }
    ```

The server will respond with `201 CREATED` response with a payload similar to the following:
```json
{
  "name": "j8m5RkjHgVjckRnr",
  "token": "SomeRandomToken",
  "expires": "2023-11-29T12:47:36.728Z"
}
```

The `token` field MUST be used as authentication header in all other private endpoints.

#### Other private APIs

In order to test private APIs, open your testing tool of choice and create a new request configured as follows:

- Base url: `http://localhost/`
- Path: use the API-specific path (public ones all starts with `api/admin/`)
- Method: can be either `GET`, `POST` or `PATCH` according to the specific API
- Headers:
    - `Authorization`, filled with the `token` field obtained from the login API 
    - `Accept` with the value of `application/json`
    - `Content-Type` with the value of `application/json`, if the request creates o updates something
- Payload is either empty or a JSON object (see Swagger for all API-specific payloads and examples)

