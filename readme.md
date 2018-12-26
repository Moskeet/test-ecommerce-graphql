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

### getCatalogs
```
query {
    getCatalogs {
        id
        name
    }
}
```
Returns all categories.

###
```
query {
    getItems (id: 1) {
        id
        name
    price
    }
}
```
Get items for specified category ID

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

### getItem
```
query {
    getItem(id: 1) {
        id
        name
        price
    }
}
```
Retrive item by it's ID

### getCategory
```
query {
    getCategory(id: 1) {
        id
        name
    }
}
```
Retrive category by it's ID

### getBasket
```
query {
    getBasket {
        totalTitles
        totalPrice
        totalItems
    }
}
```
Call should be signed with header:
```
X-AUTH-TOKEN: [token-as-is]
```

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
