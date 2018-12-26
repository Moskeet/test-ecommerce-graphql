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

### addToBasket
```
mutation {
  addToBasket(item: 1, amount: 2) {
    totalItems
    totalPrice
    totalTitles
  }
}
```
Adds an item (amount inclusive) by it's ID to basket. Amount should be greater than 0.
Call should be signed with header:
```
X-AUTH-TOKEN: [token-as-is]
```

### removeFromBasket
```
mutation {
  removeFromBasket(item: 1) {
    totalItems
    totalPrice
    totalTitles
  }
}
```
Removes position from basket by item ID.
Call should be signed with header:
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
