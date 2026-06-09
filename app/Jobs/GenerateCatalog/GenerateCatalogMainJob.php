<?php

namespace App\Jobs\GenerateCatalog;

class GenerateCatalogMainJob extends AbstractJob
{
    public function handle()
    {
        $this->debug('start');

        // Спочатку кешуємо продукти (виконується миттєво, не стає в чергу)
        GenerateCatalogCacheJob::dispatchSync();

        // Створюємо ланцюг завдань формування файлів з цінами
        $chainPrices = $this->getChainPrices();

        // Основні підзавдання
        $chainMain = [
            new GenerateCategoriesJob, // Генерація категорій
            new GenerateDeliveriesJob, // Генерація способів доставок
            new GeneratePointsJob,     // Генерація пунктів видачі
        ];

        // Підзавдання, які мають виконуваться останніми
        $chainLast = [
            new ArchiveUploadsJob,
            new SendPriceRequestJob,
        ];

        $chain = array_merge($chainPrices, $chainMain, $chainLast);

        // Запускаємо ланцюг: створюємо файл з товарами, а потім всі інші
        GenerateGoodsFileJob::withChain($chain)->dispatch();

        $this->debug('finish');
    }

    private function getChainPrices()
    {
        $result = [];
        $products = collect([1, 2, 3, 4, 5]);
        $fileNum = 1;

        foreach ($products->chunk(1) as $chunk) {
            $result[] = new GeneratePricesFileChunkJob($chunk, $fileNum);
            $fileNum++;
        }

        return $result;
    }
}
