# Mars time converter
Application to convert an earth UTC-time to mars time formats

## Quickstart


## Run and install

## API

```curl http://127.0.0.1:8000/v1/convert?date=2000-01-06T00:00:00Z```
-- 2000-01-06T00%3A00%3A00Z
Response
```json
{
    "Mars Sol Date": 1.1,
    "Martian Coordinated Time":  "01:21:59"
}
```
### Update leap seconds
```bash
wget https://www.ietf.org/timezones/data/leap-seconds.list
```