# :floppy_disk: Database schema

What follows is the main database schema, represented as ER diagram following
[Mermaid syntax](https://mermaid.js.org/syntax/entityRelationshipDiagram.html). All updates to the main schema will be
tracked in this file.

## The diagram

```mermaid
erDiagram
    users {
        UUID id PK
        VARCHAR(255) email "UNIQUE"
        VARCHAR(255) name
        VARCHAR(255) password "DEFAULT null"
        TIMESTAMP createdAt "DEFAULT null"
        TIMESTAMP updatedAt "DEFAULT null"
    }
    roles {
        UUID id PK
        VARCHAR(255) name
        TIMESTAMP createdAt "DEFAULT null"
        TIMESTAMP updatedAt "DEFAULT null"
    }
    roles }|--|{ users : roles
    travels {
        UUID id PK
        BOOL isPublic "DEFAULT false"
        VARCHAR(255) slug
        VARCHAR(255) name
        LONGTEXT description
        UNSIGNED_INT numberOfDays
        UNSIGNED_INT numberOfNights "VIRTUAL AS numberOfDays - 1"
        LONGTEXT moods "DEFAULT '[]'"
        TIMESTAMP createdAt "DEFAULT null"
        TIMESTAMP updatedAt "DEFAULT null"
    }
    tours {
        UUID id PK
        UUID travelId FK "`travels`.`id`"
        VARCHAR(255) name
        DATE startingDate
        DATE endingDate
        UNSIGNED_INT price
        TIMESTAMP createdAt "DEFAULT null"
        TIMESTAMP updatedAt "DEFAULT null"
    }
    tours }|--|| travels : travelId
```
