# Laravel hiring test

Create a Laravel APIs application similar to WeRoad structure. They will have both public and private endpoints with
roles as well.

## Glossary

- **Travel** is the basic, fundamental unit of WeRoad: it contains all the necessary information, like the number of
  days, the images, title, what's included and everything about its *appearance*. An example is `Jordan 360°`
  or `Iceland: hunting for the Northern Lights`;
- **Tour** is a specific dates-range of a travel with its own price and details. `Jordan 360°` may have a *tour* from 20
  to 27 January at €899, another one from 10 to 15 March at €1099 etc. At the end, in WeRoad, you will book a *tour*,
  not a *travel*.

## Goals

At the end, the project should have:

1. A private (admin) endpoint to create new users. If you want, this could also be an artisan command, as you like. It
   will mainly be used to generate users for this exercise;
2. A private (admin) endpoint to create new travels;
3. A private (admin) endpoint to create new tours for a travel;
4. A private (editor) endpoint to update a travel;
6. A public (no auth) endpoint to get a list of paginated travels. It must return only `public` travels;
7. A public (no auth) endpoint to get a list of paginated tours by the travel `slug` (e.g. all the tours of the
   travel `foo-bar`). Users can filter (search) the results by `priceFrom`, `priceTo`, `dateFrom` (from
   that `startingDate`) and `dateTo` (until that `startingDate`). User can sort the list by `price` asc and desc. They
   will **always** be sorted, after every additional user-provided filter, by `startingDate` asc.

## Models

**Users**

- ID
- Email
- Password
- Roles (*M2M relationship*)

**Roles**

- ID
- Name

**Travels**

- ID
- Is Public (bool)
- Slug
- Name
- Description
- Number of days
- Number of nights (virtual, computed by `numberOfDays - 1`)
- Moods (see the [samples](data_samples.md) to learn more)

**Tours**

- ID
- Travel ID (*M2O relationship*)
- Name
- Starting date
- Ending date
- Price (integer, see below)

### Notes

- Feel free to use the native Laravel authentication; don't reinvent the wheel!
- We use UUIDs as primary keys instead of incremental IDs, but it's not required for you to use them, although highly
  appreciated;
- Our tables are in `snake_case`, but their columns are in `camelCase`.
- **Tours prices** are integer multiplied by 100: for example, €999 euro will be `99900`, but, when returned to
  Frontends, they will be formatted (`99900 / 100`);
- **Tours names** inside the `samples` are a kind-of what we use internally, but you can use whatever you want;
- Every `admin` user will also have the `editor` role;
- Every *creation* endpoint, of course, should create one and only one resource. You can't, for example, send an array
  of resource to create;
- In the `samples` folder you can find JSON files containing fake data to get started with;
- Usage of *php-cs-fixer* and *larastan* are a **plus**;
- Creating docs is **big plus**;
- Feature tests are a **big big plus**.

Feel free to add to the project whatever you want! 
