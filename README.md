<h3 align="center">
  <a href="https://github.com/aejnsn/lapis"><img src="https://user-images.githubusercontent.com/5347897/31596692-2a79a572-b212-11e7-924c-356187b8ea7c.png" alt="lapis" width="175"></a>
</h3>
<h4 align="center">An API toolkit building on API Resources in Laravel 5.5.</h4>
<p align="center">
  <a href="https://travis-ci.org/aejnsn/lapis"><img src="https://travis-ci.org/aejnsn/lapis.svg?branch=master" alt="Build Status"></a>
  <a href='https://coveralls.io/github/aejnsn/lapis?branch=master'><img src='https://coveralls.io/repos/github/aejnsn/lapis/badge.svg?branch=master' alt='Coverage Status' /></a>
  <a href="https://codeclimate.com/github/aejnsn/lapis/maintainability"><img src="https://api.codeclimate.com/v1/badges/12b0e51fa30f0adaa9ea/maintainability" /></a>
</p>
<hr />

### Concept
RESTful API endpoints typically require more functionality than pagination alone. For example, a front-end or mobile application may implement a filtering scheme or require access to the endpoint's nested resources. Lapis intends to provide a lightweight filtering and nested resource pattern leveraging the existing facilities in Laravel 5.5.

#### Includes
Let's say we have an endpoint in a blogging platform to list our `post` resources returning a response like so:  
```GET https://api.myrecipeblog.com/posts```  
```json
{
  "posts": [
    {
      "name": "Asparagus Steak Oscar",
      "category": "food",
      "postedAt": "2017-09-12 16:04:33",
      "authorId": 14
    },
    {
      "name": "Churchill Downs Mint Julep",
      "category": "drinks",
      "postedAt": "2017-10-05 18:32:12",
      "authorId": 15
    }
  ]
}
```

Ideally we would want to see details of a post's author in our front-end. Right, so make another request to a hypothetical `users` endpoint referencing a distinct list of authorIds from the `posts` response...No, absolutely not! We (hopefully) spent the time in our blogging platform's backend to model our data's relationships and set up foreign key constraints with appropriate indices. So let's put those models to work.

Lapis, leveraging Laravel's API Resources, allows us to add an `include` parameter on our request URL to get retrieve the nested `author` (User) relationship. Our request URL and response would look something like this:  
```GET https://api.myrecipeblog.com/posts?include=author```  
```json
{
  "posts": [
    {
      "name": "Asparagus Steak Oscar",
      "category": "food",
      "postedAt": "2017-09-12 16:04:33",
      "authorId": 14,
      "author": {
        "id": 14,
        "name": "John Doe",
        "forHire": true,
        "chefRating": 5
      }
    },
    {
      "name": "Churchill Downs Mint Julep",
      "category": "drinks",
      "postedAt": "2017-10-05 18:32:12",
      "authorId": 15,
      "author": {
        "id": 15,
        "name": "James Smith",
        "forHire": false,
        "chefRating": 1
      }
    }
  ]
}
```
##### Nested Includes
This will also work for nested relationships. For example, if our `User` model contained a `favorites` relationship we could request
`GET https://api.myrecipeblog.com/posts?include=author.favorites` and an array of the author's `favorites` would be nested inside the `author` object.

#### Filtering
Let's go back to our original response above, for which we called the `posts` endpoint. Maybe we only want to see posts from the drinks category. We could alter our call from above like so:  
```GET https://api.myrecipeblog.com/posts?filter[category]=drinks```  
```json
{
  "posts": [
    {
      "name": "Churchill Downs Mint Julep",
      "category": "drinks",
      "postedAt": "2017-10-05 18:32:12",
      "authorId": 15
    }
  ]
}
```

We can also specify multiple filters using this structure:  
```GET https://api.myrecipeblog.com/posts?filter[category]=drinks&filter[authorId]=15```

##### Nested Filters
We discussed including an `author` relationship above. We can also filter on the author's details by making a request like so:
```GET https://api.myrecipeblog.com/posts?filter[author.forHire]=true```

##### Filter Operators
Up until this point we've just used filters to assert a field is equal to a given value. Filters understand the concept of operators. Here are a few examples:  
  
List posts later than October 1, 2017.  
```GET https://api.myrecipeblog.com/posts?filter[postedAt{gte}]=2017-10-01```  
  
List posts where the author's name starts with John.  
```GET https://api.myrecipeblog.com/posts?filter[author.name{starts}]=John```

### Reference Implementation

### Installation & Usage
