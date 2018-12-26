Calls
-----
### getAuthToken
*Allowed: Anonymous*
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
*Allowed: Anonymous*
```
query {
    getCatalogs {
        id
        name
    }
}
```
Returns all categories.

### getItems
*Allowed: Anonymous*
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
*Allowed: ROLE_USER*
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
*Allowed: ROLE_USER*
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

### payment
*Allowed: ROLE_USER*
```
mutation{
    payment{
        id
        ownerId
        owner
        description
        totalPrice
    }
}
```
Creates a transaction with all items from the basket. Basket will be removed. Extra check is added: if backet is empty -> no need to create transaction.
Call should be signed with header:
```
X-AUTH-TOKEN: [token-as-is]
```

### checkStatus
*Allowed: ROLE_USER*
```
query{
    checkStatus
}
```
Check the status of the latest transaction.
```
X-AUTH-TOKEN: [token-as-is]
```

### getTransactions
*Allowed: ROLE_ADMIN*
```
query{
  getTransactions(id: 1){
    id
    ownerId
    owner
    description
    totalPrice
  }
}
```
Retrieve all transactions by user ID

Extra calls:
------------

### getItem
*Allowed: Anonymous*
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
*Allowed: Anonymous*
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
*Allowed: ROLE_USER*
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

### getTransaction
*Allowed: ROLE_ADMIN*
```
query{
    getTransactions(id: 1){
        id
        ownerId
        owner
        description
        totalPrice
    }
}
```
Call should be signed with header:
```
X-AUTH-TOKEN: [token-as-is]
```
