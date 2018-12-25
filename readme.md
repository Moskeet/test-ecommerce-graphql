Calls
------
### getAuthToken
```
query {
  getAuthToken(username: "user2", password: "111") 
}
```
Returns token, which should be used for authed calls with header:
```
X-AUTH-TOKEN: [token-as-is]
```


Extra calls:
---
### category
```
query {
    category(id: 1){
        name
        items (first: 10){
            edges {
                cursor
                node {
                    name
                }
            }
        }
     }
}
```
Retrive category with linked items, items can be paginated.
