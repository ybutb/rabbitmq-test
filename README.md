RPC via rabbitmq with Symfony 6

To setup the project locally:

````
docker-compose build
docker-compose up -d
````

Both app-first and app-second contains a single commands:

````
bin/console app:message <int>
````

app-service simply multiplies the number by 2 and returns it back to the caller with stamp using the same request correlationId.

There is 1 queue used for requests and two queues for a responses to each app.
All the queues are bound to the same exchange in rabbitmq.

RPC clients has two separate buses in order to extract correlationId only on request.