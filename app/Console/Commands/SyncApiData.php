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
     * Commandaning nomi va argumentlari
     */
    protected $signature = 'api:sync {--dateFrom=} {--dateTo=}';

    /**
     * Commandaning tavsifi
     */
    protected $description = 'API dan barcha ma\'lumotlarni yuklab olib bazaga saqlaydi';

    /**
     * API sozlamalari
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
     * Commandani ishga tushirish
     */
    public function handle()
    {
        $this->info('🚀 API dan ma\'lumotlar yuklanmoqda...');
        $this->info('');

        // Sanalarni olish (agar berilmagan bo'lsa, oxirgi 30 kun)
        $dateFrom = $this->option('dateFrom') ?? now()->subDays(30)->format('Y-m-d');
        $dateTo = $this->option('dateTo') ?? now()->format('Y-m-d');

        $this->info("📅 Sana oralig'i: {$dateFrom} dan {$dateTo} gacha");
        $this->info('');

        // Har bir endpoint dan ma'lumotlarni yuklash
        $this->syncSales($dateFrom, $dateTo);
        $this->syncOrders($dateFrom, $dateTo);
        $this->syncStocks($dateFrom); // Faqat bugungi kun uchun
        $this->syncIncomes($dateFrom, $dateTo);

        $this->info('');
        $this->info('✅ Barcha ma\'lumotlar muvaffaqiyatli yuklandi!');
    }

    /**
     * Sotuvlarni yuklash
     */
    private function syncSales($dateFrom, $dateTo)
    {
        $this->info('📦 Sotuvlar yuklanmoqda...');
        $this->syncEndpoint('/api/sales', Sale::class, 'sale_date', $dateFrom, $dateTo);
    }

    /**
     * Buyurtmalarni yuklash
     */
    private function syncOrders($dateFrom, $dateTo)
    {
        $this->info('📋 Buyurtmalar yuklanmoqda...');
        $this->syncEndpoint('/api/orders', Order::class, 'order_date', $dateFrom, $dateTo);
    }

    /**
     * Omborlarni yuklash
     */
    private function syncStocks($dateFrom)
    {
        $this->info('🏪 Omborlar yuklanmoqda...');
        $this->syncEndpoint('/api/stocks', Stock::class, 'stock_date', $dateFrom, $dateFrom);
    }

    /**
     * Daromadlarni yuklash
     */
    private function syncIncomes($dateFrom, $dateTo)
    {
        $this->info('💰 Daromadlar yuklanmoqda...');
        $this->syncEndpoint('/api/incomes', Income::class, 'income_date', $dateFrom, $dateTo);
    }

    /**
     * Umumiy endpoint dan ma'lumot yuklash funksiyasi
     */
    private function syncEndpoint($endpoint, $modelClass, $dateField, $dateFrom, $dateTo)
    {
        $page = 1;
        $totalRecords = 0;

        do {
            try {
                // API ga so'rov yuborish
                $url = $this->apiBaseUrl . $endpoint . '?' . http_build_query([
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo,
                    'page' => $page,
                    'limit' => 500,
                    'key' => $this->apiKey
                ]);

                $this->line("   So'rov: sahifa {$page}...");

                $response = Http::timeout(60)->get($url);

                if (!$response->successful()) {
                    $this->error("   ❌ Xato: API {$response->status()} xatoni qaytardi");
                    break;
                }

                $data = $response->json();

                // Ma'lumotlar mavjudligini tekshirish
                if (empty($data['data'])) {
                    $this->line("   ℹ️ Sahifa {$page} da ma'lumot topilmadi");
                    break;
                }

                // Har bir yozuvni bazaga saqlash
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
                        Log::error("Ma'lumot saqlashda xato: " . $e->getMessage());
                    }
                }

                $this->line("   ✓ Sahifa {$page}: " . count($data['data']) . " ta yozuv saqlandi");

                // Keyingi sahifa bormi tekshirish
                if (count($data['data']) < 500) {
                    break;
                }

                $page++;
                
                // API ga ortiqcha yuklanmaslik uchun bir oz kutib turish
                sleep(1);

            } catch (\Exception $e) {
                $this->error("   ❌ Xato yuz berdi: " . $e->getMessage());
                Log::error("API sync xatosi ({$endpoint}, sahifa {$page}): " . $e->getMessage());
                break;
            }

        } while (true);

        $this->info("   📊 Jami saqlandi: {$totalRecords} ta yozuv");
        $this->info('');
    }
}
