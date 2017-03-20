# YiiPowered API 1.0

## Languages

Depending on which language you want to get descriptions in you may choose to access API via either `/en/api/1.0` for
English or `/ru/api/1.0` for Russian.

## `/projects`

Each project contains the following fields:

- id
- title
- url
- sourceUrl
- isOpenSource
- isFeatured
- yiiVersion
- createdAt
- updatedAt
- tags
- description
- thumbnail

### Full list

```
GET /projects
```

### Particular project

```
GET /projects/1
```

### Getting users along with project

Add `?expand=users` to request URL such as:

```
GET /projects?expand=users
```


## `/users`


### Full list

```
GET /users
```

### Particular user

```
GET /users/1
```