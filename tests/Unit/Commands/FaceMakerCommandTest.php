<?php

namespace Tests\Unit\Commands;

use Illuminate\Filesystem\Filesystem;
use Tests\TestCase;
use App\Console\Commands\MakeRepo;
use Illuminate\Support\Facades\Artisan;
use VivekMistry\RepositoryInterface\RepositoryGeneratorServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use VivekMistry\RepositoryInterface\Commands\MakeRepo as CommandsMakeRepo;

class FaceMakerCommandTest extends BaseTestCase
{
    protected $filesystem;
    protected $interfacePath;
    protected $repositoryPath;
    protected $testName = 'TestRepo';

    protected function setUp(): void
    {
        parent::setUp();

        // Register the service provider that registers the command
        $this->app->register(RepositoryGeneratorServiceProvider::class);

        $this->filesystem = new Filesystem();
        $this->interfacePath = app_path('Repositories/Interface/' . $this->testName . 'RepositoryInterface.php');
        $this->repositoryPath = app_path('Repositories/Repository/' . $this->testName . 'Repository.php');

        // Clean up before each test
        if ($this->filesystem->exists($this->interfacePath)) {
            $this->filesystem->delete($this->interfacePath);
        }
        if ($this->filesystem->exists($this->repositoryPath)) {
            $this->filesystem->delete($this->repositoryPath);
        }
    }

    // ... rest of the test class ...
    public function test_command_is_registered()
    {
        // Option 1: Check via Artisan (requires service provider to be registered)
        $this->assertArrayHasKey(
            'app:make-repo',
            Artisan::all(),
            'Command should be registered'
        );
    }

    public function test_command_signature()
    {
        $command = new CommandsMakeRepo(new Filesystem());
        $this->assertEquals('app:make-repo {name}', $command->getSignature());
    }

    public function test_command_properties()
    {
        $command = new CommandsMakeRepo(new Filesystem());

        $reflection = new \ReflectionClass($command);
        $property = $reflection->getProperty('signature');
        $property->setAccessible(true);

        $this->assertEquals('app:make-repo {name}', $property->getValue($command));
    }

    public function test_command_execution()
    {
        // Get the actual path the command will use
        $expectedInterfacePath = app_path('Repositories/Interface/TestRepositoryInterface.php');
        $expectedRepoPath = app_path('Repositories/Repository/TestRepository.php');

        // Clean up if files exist from previous tests
        if (file_exists($expectedInterfacePath)) {
            unlink($expectedInterfacePath);
        }
        if (file_exists($expectedRepoPath)) {
            unlink($expectedRepoPath);
        }

        $this->artisan('app:make-repo', ['name' => 'Test'])
            ->expectsOutput("File : {$expectedInterfacePath} created")
            ->expectsOutput("File : {$expectedRepoPath} created")
            ->assertExitCode(0);

        // Verify files were actually created
        $this->assertFileExists($expectedInterfacePath);
        $this->assertFileExists($expectedRepoPath);
    }
}
