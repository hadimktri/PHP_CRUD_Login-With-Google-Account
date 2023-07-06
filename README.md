# Assignment 2

1. Project's Title: PHP CRUD Operations with MySQL.

2. Project Description: CRUD operations in the web application are used to manage data dynamically. Generally, the data is stored and manipulated in the database.

MySQL tables will be created to store data for users and posts.
The MySQL repository class is a custom PHP library that handles all the CRUD-related operations (fetch, insert, update, and delete) 

3. CRUD Operations happen in the pages, update_article.php, setting.php, new_article.php, register.php, delete_article.php, delete.user.

CRUD Operations like editing and deleting using PHP and SQL. The code is executed based on the requested action in related repositories.

4. In the article.php file, we will retrieve the records from the database using MySQL class and list them in a tabular format with Add article, and Edit article options.

The posts button shows all the posts.

Add Post button directs to the new_article.php page to perform the Create operation.

The signUp button redirects to the register.php page to perform the Create operation.

Sign In button redirects to the login.php page to perform the login operation.

The edit article button redirects to the edit_articele.php page to perform the editing operation.

log out button redirects to the log_out.php page to perform the session deletions.

clicking on the profile image redirects to the profile.php page to show the user profile page.

The delete account button redirects to the delete_user.php page to delete the user with the user posts.

The Delete link redirects to the delete_article.php file with action_type=delete and id params. With the POST method, the record is deleted from the JSON file based on the unique identifier (id).

5. Included the style.css in the CSS file of the Bootstrap library to manage some specific needs.

Bootstrap Library is used to make the table, form, and buttons look better. You can omit it to use a custom stylesheet for HTML tables, forms, buttons, and other UI elements.

6. App will not accept every format and size for the profile picture.

7. App will not accept every format and size for email and password.

8. if the user exists will give an error and will not be able to duplicate the account.

9. sign in with google is added.

10. profile picture is not required app will save the basic gray avatar but cahngable later on.

11. user will have their post firs to edit.

12. posts are sorted by the creation date.
