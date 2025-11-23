<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Income;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncApiData extends Command
{
    /**
     * Имя команды и аргументы
     */
    protected $signature = 'api:sync {--dateFrom=} {--dateTo=}';

    /**
     * Описание команды
     */
    protected $description = 'Загружает все данные из API и сохраняет в базу данных';

    /**
     * Настройки API
     */
    private $apiBaseUrl;
    private $apiKey;

    public function __construct()
    {
        parent::__construct();
        $this->apiBaseUrl = env('API_BASE_URL', 'http://109.73.206.144:6969');
        $this->apiKey = env('API_KEY', 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie');
    }

    /**
     * Запуск команды
     */
    public function handle()
    {
        $this->info('🚀 Загрузка данных из API...');
        $this->info('');

        // Получение дат (если не указаны, берем последние 30 дней)
        $dateFrom = $this->option('dateFrom') ?? now()->subDays(30)->format('Y-m-d');
        $dateTo = $this->option('dateTo') ?? now()->format('Y-m-d');

        $this->info("📅 Период: с {$dateFrom} по {$dateTo}");
        $this->info('');

        // Загрузка данных из каждого эндпоинта
        $this->syncSales($dateFrom, $dateTo);
        $this->syncOrders($dateFrom, $dateTo);
        $this->syncStocks($dateFrom); // Только за сегодня
        $this->syncIncomes($dateFrom, $dateTo);

        $this->info('');
        $this->info('✅ Все данные успешно загружены!');
    }

    /**
     * Загрузка продаж
     */
    private function syncSales($dateFrom, $dateTo)
    {
        $this->info('📦 Загрузка продаж...');
        $this->syncEndpoint('/api/sales', Sale::class, 'sale_date', $dateFrom, $dateTo);
    }

    /**
     * Загрузка заказов
     */
    private function syncOrders($dateFrom, $dateTo)
    {
        $this->info('📋 Загрузка заказов...');
        $this->syncEndpoint('/api/orders', Order::class, 'order_date', $dateFrom, $dateTo);
    }

    /**
     * Загрузка складов
     */
    private function syncStocks($dateFrom)
    {
        $this->info('🏪 Загрузка складов...');
        $this->syncEndpoint('/api/stocks', Stock::class, 'stock_date', $dateFrom, $dateFrom);
    }

    /**
     * Загрузка доходов
     */
    private function syncIncomes($dateFrom, $dateTo)
    {
        $this->info('💰 Загрузка доходов...');
        $this->syncEndpoint('/api/incomes', Income::class, 'income_date', $dateFrom, $dateTo);
    }

    /**
     * Общая функция загрузки данных из эндпоинта
     */
    private function syncEndpoint($endpoint, $modelClass, $dateField, $dateFrom, $dateTo)
    {
        $page = 1;
        $totalRecords = 0;

        do {
            try {
                // Отправка запроса к API
                $url = $this->apiBaseUrl . $endpoint . '?' . http_build_query([
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo,
                    'page' => $page,
                    'limit' => 500,
                    'key' => $this->apiKey
                ]);

                $this->line("   Запрос: страница {$page}...");

                $response = Http::timeout(60)->get($url);

                if (!$response->successful()) {
                    $this->error("   ❌ Ошибка: API вернул код {$response->status()}");
                    break;
                }

                $data = $response->json();

                // Проверка наличия данных
                if (empty($data['data'])) {
                    $this->line("   ℹ️ На странице {$page} данных не найдено");
                    break;
                }

                // Сохранение каждой записи в базу данных
                foreach ($data['data'] as $item) {
                    try {
                        $modelClass::updateOrCreate(
                            [
                                'external_id' => $item['id'] ?? uniqid(),
                                $dateField => $item['date'] ?? now()->format('Y-m-d')
                            ],
                            [
                                'data' => json_encode($item),
                                $dateField => $item['date'] ?? now()->format('Y-m-d')
                            ]
                        );
                        $totalRecords++;
                    } catch (\Exception $e) {
                        Log::error("Ошибка сохранения данных: " . $e->getMessage());
                    }
                }

                $this->line("   ✓ Страница {$page}: " . count($data['data']) . " записей сохранено");

                // Проверка наличия следующей страницы
                if (count($data['data']) < 500) {
                    break;
                }

                $page++;
                
                // Небольшая задержка, чтобы не перегружать API
                sleep(1);

            } catch (\Exception $e) {
                $this->error("   ❌ Произошла ошибка: " . $e->getMessage());
                Log::error("Ошибка синхронизации API ({$endpoint}, страница {$page}): " . $e->getMessage());
                break;
            }

        } while (true);

        $this->info("   📊 Всего сохранено: {$totalRecords} записей");
        $this->info('');
    }
}
