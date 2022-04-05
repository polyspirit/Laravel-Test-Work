# TestWork3849396

## Preparations

1. Install composer. 
2. Configurate your .env file. Specify database parameters. 
3. Use commands:

`composer update`
`php artisan migrate`
`php artisan db:seed`
`php artisan users:settle`

This creates some users and cities and links them together. 
Every user have 'qwe123' password.

## Auth

Use post method for `register`, `login` and `logout` methods.
To register a user, you must provide 'email' and 'password'. A 'name' is optional.
When you login or register - api_token will be returned.
Use it as Bearer token in `Authorization` header.

```javascript
axios.get('/api/users', {
    headers: { 'Authorization': 'Bearer ' + token }
}).then(response => {
    // some code
});
```

## Requests

You can use standart REST requests for /users and /cities. 
For example:
- get: api/cities
- get: api/cities/{id}
- post: api/cities
- patch: api/cities/{id}
- delete: api/cities/{id}


## Find

You can use 'find' method for a selecting, sorting, filtering and limiting.
Example: 

```javascript
axios.post('/api/cities/find', {
    fields: ['name', 'founded', 'population'],
    filter: [
        { field: 'name', operator: 'like', value: '%stan%' },
        { field: 'founded', operator: '>', value: '1987-02-19' },
        { field: 'population', operator: '>', value: 6000000 }
    ],
    sort: { field: 'name', direction: 'DESC' },
    limit: 3
},
{
    headers: { 'Authorization': 'Bearer ' + token }
}
).then(response => {
    // some code
});
```
