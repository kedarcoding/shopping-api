<?php

namespace App\Console\Commands;

use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:userscsv {file}';
   


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users data from a CSV file into the database';

    /**
     * Execute the console command.
     */
    public function handle()
{
    $filePath = $this->argument('file');

    // Check if the file exists
    if (!file_exists($filePath)) {
        $this->error("The specified file '{$filePath}' does not exist.");
        return;
    }

    // Read the CSV file using Laravel's built-in CSV reader
    $data = \Illuminate\Support\Facades\File::get($filePath);
    $csvData = array_map('str_getcsv', explode("\n", $data));
    array_shift($csvData);
    $count=0;
    // Process and import data into the database
    
    foreach ($csvData as $row) {
        $count++;
        // Process each row as needed
        // Example: Create a new model and save it to the database
        try{
            User::create([
                'first_name' => $row[0],
                'last_name' => $row[1],
                'phone_number' => $row[2],
                'email' => $row[3],
                'password' => $row[0],
                'created_at'=>now(),
                'updated_at'=>now()
                // Add more columns as needed
            ]);
        }catch(Exception $error){
            Log::error($error);
        }
    }
    Log::info("Inserted users {$count} record(s) into the database using csv command line.");
    $this->info('CSV data imported successfully.');
}

}
