<?php

namespace NathanBurkett\Ecosystem\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\AppNamespaceDetectorTrait;
use Symfony\Component\Console\Input\InputArgument;

class GenerateNewEcosystemCommand extends Command
{
    use AppNamespaceDetectorTrait;

    /**
     * Name and signature of the console command;
     * @var string
     */
    protected $signature = 'make:ecosystem {name} {--namespace=App\Library\Ecosystems}';

     /**
      * Console command description
      * @var string
      */
    protected $description = 'Create a new Ecosystem class';

     /**
      * Filesystem instance
      * @var \Illuminate\Filesystem\Filesystem
      */
    protected $files;

     /**
      * New command instance
      * @param \Illuminate\Filesystem\Filesystem $files
      * @param \Illuminate\Support\Composer      $composer
      */
    public function __construct(Filesystem $files)
    {
         parent::__construct();

         $this->files = $files;
         $this->composer = app()['composer'];
    }

    /**
     * Execute console command
     * @return void
     */
    public function handle()
    {
        // Check if Ecosystem exists on the
        // name argument
        $name = $this->standardizeName();

        $this->outputEcosystemFile($name);
    }

    /**
     * Check if name argument has Ecosystem at end
     * @return string
     */
    protected function standardizeName()
    {
        $name = $this->argument('name');

        return strpos($name, 'Ecosystem') ? $name : $name . 'Ecosystem';
    }

    /**
     * Output the Ecosystem stub if doesn't exist
     * @param  string $class
     * @return void
     */
    protected function outputEcosystemFile($class)
    {
        if ($this->files->exists($path = $this->getPath($class))) {
            return $this->error($class . ' already exists!');
        }

        $this->putFile($class, $path);
    }

    /**
     * Get the location for putting Ecosystem stub
     * @param  string $class
     * @return string
     */
    protected function getPath($class)
    {
        return base_path(lcfirst(str_replace('\\', '/', $this->option('namespace'))) . DIRECTORY_SEPARATOR . $class . '.php');
    }

    /**
     * Put Ecosystem stub in place via namespace in config
     * @param  string $name
     * @param  string $path
     * @return void
     */
    protected function putFile($name, $path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true);
        }

        $this->generateEcosystemClass($name, $path);

        $this->successMethods($name);
    }

    /**
     * Generate the Ecosystem class from the stub
     * @param  string $name
     * @param  string $path
     * @return void
     */
    protected function generateEcosystemClass($name, $path)
    {
        $stub = $this->addArguments($this->files->get(__DIR__ . '/../stubs/ecosystem.stub'), $name);

        $this->files->put($path, $stub);
    }

    /**
     * Read contents of stub and replace placeholders with arguments
     * @param string $stub
     * @return string
     */
    protected function addArguments($stub, $name)
    {
        $stub = str_replace('{{class}}', $name, $stub);
        $stub = str_replace('{{namespace}}', $this->option('namespace'), $stub);

        return $stub;
    }

    /**
     * Output console feedback on success and dump autoloads
     * @param  string $name
     * @return void
     */
    protected function successMethods($name)
    {
        $this->info("{$name} created successfully");

        $this->composer->dumpAutoloads();
    }
}
