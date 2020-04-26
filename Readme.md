# Mars time converter
Application to convert an earth UTC-time to mars time formats.

## Quickstart

```bash
composer install
wget https://www.ietf.org/timezones/data/leap-seconds.list
symfony local:server:start --no-tls
```
Test instance is running at http://127.0.0.1:8000

### Update leap seconds
If new leap seconds are getting published run the following command:
```bash
wget https://www.ietf.org/timezones/data/leap-seconds.list
```

## API
### GET /v1/convert
Converts a date to mars sol date and martian coordinated time.

Only times after 1972-01-01 are supported.

#### Example
```bash
curl http://127.0.0.1:8000/v1/convert?date=2000-01-06T00:00:00Z
```
#### Query-parameter

| Name | Description |
|------|-------------|
| date | Must be in ISO8601 date time format. I.e. 2000-01-01T23:59:59Z |

#### Response
```json
{
    "Mars Sol Date": 1.1,
    "Martian Coordinated Time":  "01:21:59"
}
```