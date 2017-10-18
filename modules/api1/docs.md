# YiiPowered API 1.0

## Languages <a href="#languages" id="languages">#</a>

Depending on which language you want to get descriptions in you may choose to access API via either [/en/api/1.0](/en/api/1.0) for
English or [/ru/api/1.0](/ru/api/1.0) for Russian.

## `/projects` <a href="#projects" id="projects">#</a>

### Project object <a href="#projects-object" id="projects-object">#</a>

Each project contains the following fields:

- `id` - ID of the project
- `title` - name of the project
- `url` - URL of the main project website
- `sourceUrl` - URL of project source. Typically GitHub or BitBucket
- `isOpenSource` - `1` if project is OpenSource. `0` if it is not.
- `isFeatured` - `1` if project is featured. `0` if it is not.
- `yiiVersion` - Yii framework version used. Either `1.1` or `2.0`.
- `createdAt` - UNIX timestamp indicating when project was added to YiiPowered.
- `updatedAt` - UNIX timestamp indicating when project info was updated last time.
- `tags` - an array of tags.
- `description` - project description. Note that it may be in different language depending on which URL you are using
  for API calls. See [Languages](#languages).
- `thumbnail` - URL pointing to project thumbnail image.
- `users` - users participated in the project.
- `votingResult` - voting result for a project.

### Particular project <a href="#projects-view" id="projects-view">#</a>

In order to get particular project, use the following request:

> GET [/projects/1](/en/api/1.0/projects/1)

In the above `1` is the project ID.

### Update project <a href="#projects-update" id="projects-update">#</a>

In order to update project, use the following request: 

> PUT [/projects/1](/en/api/1.0/projects)

In the above `1` is the project ID.

- `title` - Name of the project.
- `url` - URL of the main project website.
- `is_opensource` - `1` if project is OpenSource. `0` if it is not.
- `source_url` - URL of project source. Typically GitHub or BitBucket.
- `yii_version` - Yii framework version used. Either `1.1` or `2.0`.
- `description` - Project description. Note that it may be in different language depending on which URL you are using
  for API calls. See [Languages](#languages).
- `status` - It can take the following values: 
    - `0`: deleted; 
    - `10`: draft;
    - `20`: published.
- `primary_image_id` - ID of the primary image.
- `tagValues` - Tags of the project.

### Delete project <a href="#projects-delete" id="projects-delete">#</a>

In order to delete project, use the following request: 

> DELETE /projects/1

In the above `1` is the project ID.


### List <a href="#projects-index" id="projects-index">#</a>

In order to list projects use the following request:


> GET [/projects](/en/api/1.0/projects)


### Filtering list <a href="#projects-search" id="projects-search">#</a>

You may pass additional parameters when querying a list:

> GET [/projects?isOpenSource=1](/en/api/1.0/projects?isOpenSource=1)

- `tags` - comma separated list of tags that should be used to tag a project in order for it to be returned.
- `title` - a string that should be contained within project title.
- `url` - a string that should be contained within project URL.
- `isOpenSource` - `1` if only OpenSource projects should be returned. If the value is omitted or is `0`, all projects are
  returned.
- `isFeatured` - `1` if only Featured projects should be returned. If the value is omitted or is `0`, all projects are
  returned.
- `yiiVersion` - version of the framework project built with. Either `1.0` or `1.1`.


### Voting a project <a href="#projects-vote" id="projects-vote">#</a>

In order to vote a project, use the following request:

> PUT [/projects/1/vote](/en/api/1.0/projects/1/vote)

In the above `1` is the project ID.

- `value` - `-1` if thumbs down and `1` if thumbs up.

Return data:

- `votingResult` - voting result for a project.

## `/users` <a href="#users" id="users">#</a>

### User object <a href="#users-object" id="users-object">#</a>

Each user object contains the following fields:

- `id` - user ID
- `username` - nickname
- `fullname` - full name
- `github` - GitHub
- `twitter` - Twitter
- `facebook` - Facebook

### Particular user <a href="#users-view" id="users-view">#</a>

In order to get particular user, use the following request:

> GET [/users/1](/en/api/1.0/users/1)

In the above `1` is the user ID.



### List <a href="#users-index" id="users-index">#</a>

In order to list users use the following request:


> GET [/users](/en/api/1.0/users)

## `/bookmarks` <a href="#bookmarks" id="bookmarks">#</a>

Users are able to add or remove projects to bookmarks (favorites).

### Bookmark object

- `createdAt` - UNIX timestamp of when a project was bookmarked
- `project` - expanded project object.
 
### Current user bookmarks

In order to get current user bookmarks use the following request:

> GET [/bookmarks](/en/api/1.0/bookmarks)

### Bookmark a project

In order to add a project to bookmarks use the following request:

> POST /bookmarks

  - id: id of a project to bookmark

### Remove a project from bookmarks

In order to remove a project from bookmarks issue the following request:

> DELETE /bookmarks/91
