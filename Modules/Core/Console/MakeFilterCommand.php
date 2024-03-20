<?php

namespace Modules\Core\Console;

use Illuminate\Support\Str;
use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MakeFilterCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';
    protected $name = 'module:make-filter';
    protected $description = 'Create a new filter class for the specified module.';

    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.filters.namespace') ?: $module->config('paths.generator.filters.path', 'Filters');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the filter class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['abstract', 'a', InputOption::VALUE_NONE, 'Create a filter abstract class name.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'NAMESPACE' => $this->getClassNamespace($module),
            'CLASS'     => $this->getClass(),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $resourcePath = GenerateConfigReader::read('filters');

        return $path . 'Filters/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName(): string
    {
        $fileName = Str::studly($this->argument('name'));

        return $this->option('abstract')
            ? $fileName . 'Filters'
            : $fileName . 'Filter';
    }

    /**
     * Determine if the command is generating a abstract base class filter.
     *
     * @return bool
     */
    public function abstractClass(): bool
    {
        return $this->option('abstract');
    }

    /**
     * @return string
     */
    protected function getStubName(): string
    {
        if ($this->abstractClass()) {
            return '/filter-abstract.stub';
        }

        return '/filter.stub';
    }
}
