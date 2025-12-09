<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateUseCase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:usecase
                            {domain : Nome do domínio (ex: User, Product)}
                            {action : Nome da ação (ex: CreateUser, DeleteProduct)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria um UseCase e sua Interface seguindo a arquitetura recomendada';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $domain = Str::studly($this->argument('domain'));
        $action = Str::studly($this->argument('action'));

        if ($domain == "" || $action == "") {
            $this->error("Domínio e Ação são obrigatórios.");
            return Command::FAILURE;
        }

        $basePath = app_path("UseCases/{$domain}/{$action}");
        $useCaseClass = "{$action}UseCase.php";
        $interfaceClass = "I{$action}UseCase.php";

        // Criar pastas se não existirem
        if (!File::exists($basePath)) {
            File::makeDirectory($basePath, 0755, true);
        }

        // Criar UseCase
        $useCaseContent = $this->generateUseCaseContent($domain, $action);
        File::put("{$basePath}/{$useCaseClass}", $useCaseContent);

        // Criar Interface
        $interfaceContent = $this->generateInterfaceContent($domain, $action);
        File::put("{$basePath}/{$interfaceClass}", $interfaceContent);

        $this->info("UseCase criado: {$useCaseClass}");
        $this->info("Interface criada: {$interfaceClass}");

        return Command::SUCCESS;
    }

    /**
     * @param string $domain Domínio da ação
     * @param string $action Nome da ação
     * @return string Conteúdo da classe UseCase
     */
    private function generateUseCaseContent(string $domain, string $action): string
    {
        return <<<PHP
<?php

namespace App\UseCases\\{$domain}\\{$action};

class {$action}UseCase implements I{$action}UseCase
{
    public function handle(\$request)
    {
        // TODO: Implement logic
    }
}
PHP;
    }

    /**
     * @param string $domain Domínio da ação
     * @param string $action Nome da ação
     * @return string Conteúdo da Interface
     */
    private function generateInterfaceContent(string $domain, string $action): string
    {
        return <<<PHP
<?php

namespace App\UseCases\\{$domain}\\{$action};

interface I{$action}UseCase
{
    public function handle(\$request);
}
PHP;
    }
}
