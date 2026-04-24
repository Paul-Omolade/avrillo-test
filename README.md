# Avrillo SDLT Calculator

A small Laravel 12 + Vue 3 Stamp Duty Land Tax (SDLT) calculator for residential property purchases in England.

It supports:

- Standard residential SDLT
- First-time buyer relief
- Additional property surcharge

The calculator shows:

- Total SDLT due
- Effective tax rate
- A plain-English band-by-band breakdown

## Requirements

Before running the project, make sure you have the following installed on your machine:

- PHP 8.2 or higher
- Composer
- Node.js 20 or higher
- npm
- SQLite

## Setup

### 1. Clone the repository

```bash
git clone https://github.com/Paul-Omolade/avrillo-test.git
cd avrillo-test
````

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install frontend dependencies

```bash
npm install
```

### 4. Create the environment file

If `.env` does not already exist, create it from the example file:

```bash
cp .env.example .env
```

On Windows PowerShell, use:

```powershell
Copy-Item .env.example .env
```

### 5. Generate the application key

```bash
php artisan key:generate
```

### 6. Configure the database

This project uses SQLite for simplicity.

Open `.env` and make sure these values are set:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### 7. Create the SQLite database file

#### On Windows PowerShell

```powershell
New-Item database/database.sqlite -ItemType File -Force
```

#### On macOS / Linux

```bash
touch database/database.sqlite
```

### 8. Clear cached configuration

```bash
php artisan optimize:clear
```

### 9. Run database migrations

```bash
php artisan migrate
```

## Running the application

You need to run both the Laravel development server and the Vite frontend server.

### 1. Start the Laravel server

```bash
php artisan serve
```

This will usually start the app at:

```text
http://127.0.0.1:8000
```

### 2. Start the Vite frontend server

In a separate terminal, run:

```bash
npm run dev
```

### 3. Open the application in your browser

Open:

```text
http://127.0.0.1:8000
```

Do not use the Vite URL directly for the app. Laravel serves the page, and Vite serves the frontend assets in the background.

## Running tests

### Run all tests

```bash
php artisan test
```

### Run unit tests only

```bash
php artisan test tests/Unit
```

### Run feature tests only

```bash
php artisan test tests/Feature
```

### Run tests with readable output

```bash
php artisan test --testdox
```

## Example inputs and expected outputs

These example inputs can be used to quickly check the calculator behaviour.

### 1. Standard residential purchase

* Property price: `295000`
* Purchase date: `2026-04-24`
* First-time buyer: `false`
* Additional property: `false`
* Expected SDLT: **£4,750**

### 2. First-time buyer purchase

* Property price: `500000`
* Purchase date: `2026-04-24`
* First-time buyer: `true`
* Additional property: `false`
* Expected SDLT: **£10,000**

### 3. Additional property purchase

* Property price: `300000`
* Purchase date: `2026-04-24`
* First-time buyer: `false`
* Additional property: `true`
* Expected SDLT: **£20,000**

### 4. Standard residential threshold case

* Property price: `250000`
* Purchase date: `2026-04-24`
* First-time buyer: `false`
* Additional property: `false`
* Expected SDLT: **£2,500**

## Notes

* This calculator applies current residential SDLT rules for England only.
* Historical rates are not implemented.
* Non-residential and mixed-use purchases are out of scope.
* Leasehold rent calculations are out of scope.
* Linked transactions, MDR, LBTT, LTT, and non-resident surcharge are out of scope.
* Purchase date is collected and shown in the UI, but this version uses the current rate configuration only.
