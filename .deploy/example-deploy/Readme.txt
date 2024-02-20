This is an example of deploy using the pre-built open-social-care/backend Docker image.

The image open-social-care/backend is built by github workflow triggered by pushes on staging branch, then uploaded to ghcr.io.

This example uses a dockerfile to simulate deploy locally. To run it:
$ cp .env.staging.example .env
-> Edit .env by adding an APP_KEY
$ docker compose up

Laravel, mailpit and postgres services will be started.

In the first execution, you must seed database:
- Enter laravel container shell
- Run: $ php artisan db:seed

API Documentation can be used for testing deploy:
- Access: http://localhost/api/documentation
- Try login/reset password using Seeded users (admin@socialcare.com, 123456)