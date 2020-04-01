# Architecture
## Domains
This appliction is made up from two domains, one is being **CART** the other **CATALOGUE**. Moreover
there is another common namespace called **COMMON**

### Cart Domain
Namespace: `App\Shop\Cart\`<br>

This domain defines cart model and product reference model under `Domain\Model`
and defines how to:
 - create cart
 - add products to cart
 - remove products to cart
 - delete cart
 
This domain is unaware of real products, it just stores references to them. 
It also defines domain events under `Domain\Events`

### Catalogue Domain
Namespace: `App\Shop\Catalogue\`<br>

This domain defines product model under `Domain\Model`
and defines how to:
 - create product
 - how to modify product properties and when it's allowed
 - deletes product

This domain is unaware of cart domain. 
It also defines domain events under `Domain\Events`

### Common
Namespace: `App\Shop\Common`<br>

It stores shared classes like Price, Base Domain Event or reusable ParserInterface

## Hexagonal
The `Domain\Model` in both domains is totally unaware of infrastructure application layer or
other domains.

## CQRS
Under `Application` in both domains there are defined Commands and their Handlers
Under `Infrastrucutre\Query` in both domains there are defined Queries for each domain

## Events
Each Domain model state change creates specified to operation domain event which is later saved
to database, so there is full history of change of every model. They are being persisted in single
database transaction as domain model change so there are never missed or broken
