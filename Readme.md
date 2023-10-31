# Setting up the Development Environment

Install both frontend and backend dependencies :
 `composer install && npm install`

Create a `.env` file from `.env.example`. For Google credentials, we recommend setting up a sandbox on `localtest.me` (points to `localhost`) or setting up any `.local` domain. For convenience, you can use the sandbox project on `dev.do@e5vos.hu` if you have access.

Run both backend server and frontend servlet at the same time:
`php artisan serve`
`npm run dev`

**Important!: The application is accessible via the backend's URL. Frontend URL for development tooling's internal use only.**

# Contributing

After creating your contribution simply make a pull request to the master branch.
