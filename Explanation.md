# Cool Kids Network

This plugin adds new features that allows users register into the website with a character info created from of a randomly generated data and visualize other users' characters. It provides three new roles Cool Kid, Cooler Kid and Coolest Kid roles.

### How it works
The system uses [randomuser.me](https://randomuser.me/) API's service to get a randomly generated user data that contains name and location. It then creates a unique user login from the combination of first and last name. A WordPress user is then created with those profile data. The country is then extracted from `location` attribute of the random user data and stored as the user meta value for `country` meta key. The default role for the user created is `cool_kid`.

### Roles
Cool Kid - The default role assigned to a newly created user. It only has a permission to view user's own character information.

Cooler Kid - Has all capabilities of `Cool Kid` plus viewing other users' name and country. It has `view_other_users_name` and `view_other_users_country` custom capabilities.

Coolest Kid - Has all capabilities of `Cooler Kid` plus `view_other_users_email` and `view_other_users_role` custom capabilities.

### Shortcodes
The following shortcodes can be used on any page to display information about characters or signup/login forms.
`[ckn-show-character-info]` - Displays the current user's character details.<br>
`[ckn-list-users]` - Displays a list of other users.<br>
`[ckn-signup-form]` - Displays a sign up form that allows users create a new user account to anonymous users.

### API
The plugin provides a new API route that allows updating user roles for authenticated requests and valid roles. The new route is found at `ckn/{API_VERSION}/user_role`. A user email address and new role must be provided to update a users role. Valid roles maintained with this route are limited to `cool_kid`, `cooler_kid` and `coolest_kid`.