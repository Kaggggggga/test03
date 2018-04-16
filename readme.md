# Url shortener
```
- 1. RESTful endpoint for url submission
- POST /submit
- req: { "url": "http://www.google.com" }
- response: { "url": "http://www.google.com", "shorten_url":"http://shorturl.com/aSxgd5ga" }

- 2. Shorten redirect URL
- GET /[a-zA-Z0-9]{8} (regex, eg. aSxgd5ga)
- HTTP 301 to saved link (eg. http://www.google.com according previous example)
- No update on the shorten link once created
```

