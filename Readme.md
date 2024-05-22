👋 Hey there!
=============

Welcome to our Symfony user management project! We built this project to help you manage users and their associated companies. Plus, we threw in some fun features like password reset and a snazzy menu. 🚀

Here's what you'll find in this project:

- User management with Symfony's built-in security features
- Company management with a one-to-many relationship to users
- Login and password reset functionality using symfonycasts/reset-password-bundle
- A slick menu using knplabs/knp-menu-bundle
- Beautiful styling with Tailwind CSS
We generated all the pages using Symfony's make:crud command, so you know it's good stuff. 😎

To get started with this project, you'll need to run the following commands:

```bash
docker run --rm --interactive --tty --user $(id -u):$(id -g) --volume $PWD:/app composer create-project kiora-tech/user-app my_project
cd my_project
make install_symfony
```

The make install_symfony command will create the database and load some fixtures to help you get started.

Once the project is installed, you can access it at localhost:8080 if you're using Docker.

Here are some additional make commands that you might find useful:
* make php
* make test
* make

We hope you enjoy using this project as much as we enjoyed building it! If you have any questions or run into any issues, don't hesitate to reach out. We're always here to help. 🤗

Happy coding! 💻

Note: This README is just a draft, and you can customize it further to fit your project's specific needs and tone. Also, make sure to replace the project name and version number in the command with your actual project name and version.

Hope this helps! Let me know if you have any other questions. 😊