<p align="center">
    <img src="public/images/logo.png" width="200" alt="CarMarket logo" />
</p>

# CarMarket

CarMarket ir moderns Latvijas auto sludinājumu portāls, veidots ar Laravel 12 un Tailwind CSS. Projekts piedāvā ērtu veidu, kā
pievienot, pārlūkot un pārvaldīt auto sludinājumus, akcentējot tīru un viegli pārskatāmu dizainu.

## Galvenās iespējas

- Pilns autentifikācijas komplekts, ieskaitot reģistrāciju, pieslēgšanos un paroles atjaunošanu.
- Sludinājumu izveide, rediģēšana un dzēšana ar vairāku attēlu augšupielādi.
- Favorītu saraksts un personīgais sludinājumu pārskats.
- Tumšais režīms un pielāgojami filtri auto galerijai.
- Administrēšanas panelis sludinājumu un lietotāju pārvaldībai.

## Tehnoloģijas

- [Laravel](https://laravel.com/) 12 ar Breeze autentifikācijas startkitu.
- [Tailwind CSS](https://tailwindcss.com/) stilam un tumšajam režīmam.
- [Vite](https://vitejs.dev/) aktīvu bundleris un moderns JavaScript darbplūsmas nodrošinātājs.

## Uzstādīšana

```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate --seed
npm run build # vai npm run dev izstrādei
php artisan serve
```

Projekta grafiskie aktīvi (tai skaitā logo) atrodas mapē `public/images`.

## Licence

Projekts ir pieejams saskaņā ar [MIT licenci](LICENSE).
