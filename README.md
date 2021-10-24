
# Clap app API

A brief description of what this project does and who it's for


## API Reference

#### Register a new user

```http
  POST /api/v1/auth/register
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required**.  |
| `username` | `string` | **Required**.  |
| `email` | `string` | **Required**.  |
| `phone` | `string` | **Required**.  |
| `password` | `string` | **Required**.  Passwords must contain a mix of upper and lower case letters|
| `is_stall` | `boolean` | **Optional**. Required only when registering a new stall |

#### Log a user in

```http
  POST /api/v1/auth/login
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `email`      | `string` | **Required**.         |
| `password` | `string` | **Required**.  |

#### Get currently authenticated user


  