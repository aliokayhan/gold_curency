# Currency Freaks Örnek Projesi

Bu proje, Currency Freaks API kullanarak döviz kurlarını takip eden ve altın fiyatlarını gösteren bir Laravel v12 uygulamasıdır.

## Özellikler

- Altın fiyatlarını gerçek zamanlı takip
- Alış/Satış marj yönetimi
- Otomatik veri güncelleme (Her dakika)
- RESTful API endpoints
- Veritabanında fiyat geçmişi

## Gereksinimler

- PHP 8.2 veya üzeri
- Docker ve Docker Compose
- Currency Freaks API anahtarı
- Composer

## Kurulum

1. Projeyi klonlayın:
```bash
git clone [proje-url]
cd currency-freaks-example
```

2. `.env` dosyasını oluşturun:
```bash
cp .env.example .env
```

3. `.env` dosyasını düzenleyin:
```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=currency_freaks
DB_USERNAME=your_username
DB_PASSWORD=your_password

CURRENCY_FREAKS_API_KEY=your_api_key
```

4. Docker container'larını başlatın:
```bash
docker-compose up -d --build
```

5. Uygulamanın çalıştığını kontrol edin:
```bash
docker-compose ps
```

## Kullanım

- Web arayüzü: `http://localhost:8000`
- API endpoint: `http://localhost:8000/gold-price`

## API Kullanımı

Altın fiyatlarını almak için:
```bash
curl http://localhost:8000/gold-price
```

Örnek yanıt:
```json
{
    "base": "USD",
    "currency": "XAU",
    "buy_price": 1923.45,
    "sell_price": 1920.30,
    "last_updated": "2024-01-01T12:00:00+00:00",
    "history": [
        {
            "timestamp": "2024-01-01T11:55:00+00:00",
            "price": 1922.50
        }
    ]
}
```

## Scheduler Yapılandırması

Laravel v12'de scheduler yapılandırması `routes/console.php` dosyasında bulunur. Uygulama her dakika Currency Freaks API'den güncel fiyatları çeker:

```php
Schedule::command('gold:fetch-price')
    ->everyMinute()
    ->appendOutputTo(storage_path('logs/scheduler.log'));
```

Scheduler loglarını kontrol etmek için:
```bash
tail -f storage/logs/scheduler.log
```

## Sorun Giderme

1. Container loglarını kontrol etme:
```bash
docker-compose logs -f
```

2. Scheduler'ın çalıştığını kontrol etme:
```bash
php artisan schedule:list
```

3. Manuel olarak veri çekme komutu:
```bash
php artisan gold:fetch-price
```

4. Laravel loglarını kontrol etme:
```bash
tail -f storage/logs/laravel.log
```

## Lisans

Bu proje MIT lisansı altında lisanslanmıştır.
