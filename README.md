# Vending Machine Challenge

## How to run this project
  - Install dependencies: ```composer install```
  - Run the console application: ```php bin/console app:vending-machine```

## How it works
  - Get water product without exact change: 
  ```0.25, 0.25, 0.10, 0.10, GET-WATER```
  ```SODA. 0.05```
  
  - Get soda product with exact change:
  ```1, 0.25, 0.25, GET-SODA```
    ```SODA```
    
  - Return coins:
  ```1, 0.25, 0.25, RETURN-COIN```
    ```1, 0.25, 0.25```
    
  - Set available change coins:
  ```1, 0.25, 0.25, 0.10, 0.10, SERVICE```
    ```AVAILABLE-CHANGE, 1, 0.25, 0.25, 0.10, 0.10```
    
  - Set water product stock:
  ```20, WATER, SERVICE```
    ```WATER, 20``` 
  
## How to Test
  - Phpspec: ```make phpspec```
  - Phpunit: ```make phpunit```  

## Requirements
  - Composer
  - Php

  

