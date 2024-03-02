# LanOps Event Management Platform

Born from the ashes of Eventula comes the next iteration of the Lan Management Platform. Built For LanOps.


### Steps

- ```cp src/.env.example src/.env```
- Fill that sucker in
- ```make build```
- ```docker compose up -d```


### TODO

- upgrade NPM - using node8!!!
- re do frontend
- fix initial setup
- move models to models/
- move images into app/resources and have gulp pull them in
- move all assets in public into app/resources and have gulp sort them
- - Maybe move them into storage?
- - Maybe move away from gulp?
- Re-evaluate what is needed from NPM
- Remove event_tag table?