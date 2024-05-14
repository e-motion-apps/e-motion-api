<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\OpenAIService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCityRulesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use SerializesModels;
    use Batchable;

    public function __construct(
        private array $cityData,
        private bool $force,
    ) {}

    public function handle(OpenAIService $openAIService): array
    {
        return $openAIService->importRulesForCity($this->cityData, $this->force);
    }
}
