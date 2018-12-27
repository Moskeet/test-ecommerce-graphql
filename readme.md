Installation:
-------------
- run (first time)
  ```
  $ docker-compose up --build
  ```
  or (each next time, as folders with DB will be used from previous run)
  ```
  $ docker-compose up
  ```
- check hash of the containers
  ```
  $ docker ps
  ```
  we need 'php' container's ID
- login into 'php' container's bash
  ```
  $ docker exec -it [container-id] /bin/bash
  ```
  we need to create users inside this container
  - run
    ```
    # composer install
    ```
    to install all dependencies
  - run
    ```
    # bin/console doctrine:migrations:migrate
    ```
    to create all required tables via migrations
  - run
    ```
    # bin/console fos:user:create
    ```
    you'll be prompted for username + email + password
    
    use this command multiple times, to create 'admin' + some other users
  - run
    ```
    # bin/console fos:user:promote
    ```
    you'll be prompted for username + role
    
    please use username of your admin, and role should be `ROLE_ADMIN`

  Initial setup finished.
- let's check setup, open your browser and use an url there `http://localhost:8080/graphiql`, you will see a client for graphql server (unfortunately it can't work with headers)

  but you still can check some calls without authorization here
- use a client for GraphQL which supports headers (I've used `Altair` extension for Chrome)
  
Now you may check calls, according to requirements. Please *note* that `getAuthToken` is also a GraphQL call, username + password should be the same, you've specified at user creation.

All signed calls should use a header   
```
X-AUTH-TOKEN: [token-as-is]
```

Calls
-----
### getAuthToken
*Allowed: Anonymous*
```
query {
  getAuthToken(username: "user2", password: "111") 
}
```
Returns token, which should be used for authed calls with header.

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
Edge items can be used here.

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
Edge basketItems can be used here.
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
Edge basketItems can be used here.
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
Returns the status of the latest transaction.
Call should be signed with header:
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
Calls which were added due to functionality testing, but they are useful.


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
Retrive category by it's ID.
Edge items can be used here.

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
Edge basketItems can be used here.
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
