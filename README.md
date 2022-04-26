
# Laravel Expense Sharing API Using Laravel Jetstream with Sanctum

A Expense Sharing Application API.Used to Share Expese between users / friends based on
Share Basis : EQUAL, EXACT, PERCENT.




## Installation
Run the following Commands to install the Dependencies

```bash
  composer install
  npm install
```

## API Reference

#### Authentication
this will return your access token for your rest of the requests

```http
  POST /api/login  
```
```
  BODY [form-data] : Email , Password
```  
```
  RETURN FORMAT :
  {
      "access_token": "xxxxxxxxxxxxxxxxxxxxxx", //access token
      "token_type": "Bearer"
  }

```
----


#### Add Expenses
This is used to insert Expense and Shares in Database. You will have to Send your Authorization token
in Header and Return Format will be JSON.

```http
  POST api/expense/add_expense
```
```
  HEADERS  :
   Authorization : Bearer xxxxxxxxxxxxxxxxxxxxxx 
   Content-Type : application/json
   Accept   : application/json
```  
xxxxxxxxxxxxxxxxxxxxxx is your ACCESS TOKEN.
```
  BODY [JSON] : // Three Formats EQUAL, EXACT, PERCENT

  EQUAL SHARE JSON :
    
    {
      "name" : "Travel",
      "amount" : 1200,
      "share_type": "EQUAL",
      "shares" : [
        {
          "friend_ids" : [2,3,4]
        }
      ]
    }

  
  EXACT SHARE JSON :

  {
    "name" : "Shopping",
    "amount" : 1000,
    "share_type": "EXACT",
    "shares" : [
      {
        "friend_id" : 2,
        "share_amount" : 200
      },
      {
        "friend_id" : 3,
        "share_amount" : 200
      },
      {
        "friend_id" : 4,
        "share_amount" : 600
      }
    ]
  }

  ----

  PERCENT SHARE JSON : 

  {
    "name" : "Grocery",
    "amount" : 1000,
    "share_type": "PERCENT",
    "shares" : [
      {
        "friend_id" : 2,
        "share_percent" : 20
      },
      {
        "friend_id" : 3,
        "share_percent" : 30
      },
      {
        "friend_id" : 4,
        "share_percent" : 50
      }
    ]
  }
  
```  
```
  RETURN FORMAT :
  {
      "success": true,
      "msg": "Expense Has been Stored"
  }

```

----  

#### GET BALANCES
This will Return Sum of All Expenses Made by Authenticated user and Also Balances
array which contains sum of Money each users owes Authenticated User.

```http
  GET api/expense/get_expenses
```
```
  HEADERS  :
   Authorization : Bearer xxxxxxxxxxxxxxxxxxxxxx 
   Content-Type : application/json
   Accept   : application/json
``` 
```
  RETURN FORMAT :
  {
      "total_expenses": "3200.00",
      "balances": [
          "User2 Owes User1 a Total of 800",
          "User3 Owes User1  a Total of 900",
          "User4 Owes User1  a Total of 1500"
      ],
      "details": [
          {
              "friend": "User2",
              "friend_id": 2,
              "email": "user2@mailinator.com",
              "balance": 800
          },
          {
              "friend": "User3",
              "friend_id": 3,
              "email": "user3@mailinator.com",
              "balance": 900
          },
          {
              "friend": "User4",
              "friend_id": 4,
              "email": "user4@mailinator.com",
              "balance": 1500
          }
      ]
  }

```
## Screenshots

#### AUTHENTICATE

![App Screenshot](https://i.ibb.co/kGZ90YL/Screenshot-2022-04-26-at-7-53-29-PM.png)

#### ADD EXPENSE :: EQUAL SHARES
![App Screenshot](https://i.ibb.co/pQjKy2m/Screenshot-2022-04-26-at-7-57-27-PM.png)

#### ADD EXPENSE :: EXACT SHARES
![App Screenshot](https://i.ibb.co/9cc2TBL/Screenshot-2022-04-26-at-7-59-07-PM.png)

#### ADD EXPENSE :: PERCENT SHARES
![App Screenshot](https://i.ibb.co/nCTpBvH/Screenshot-2022-04-26-at-8-00-04-PM.png)

#### GET EXPENSES WITH BALANCES :: GET EXPENSES
![App Screenshot](https://i.ibb.co/vLd2JkR/Screenshot-2022-04-26-at-8-05-03-PM.png)


## Authors

- [@imramandeep](https://www.github.com/imramandeep)


## 🔗 Links
[![portfolio](https://img.shields.io/badge/my_portfolio-000?style=for-the-badge&logo=ko-fi&logoColor=white)](https://itsraman.com/)
[![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/erramandeep/)
[![twitter](https://img.shields.io/badge/twitter-1DA1F2?style=for-the-badge&logo=twitter&logoColor=white)](https://twitter.com/imramandeep)

