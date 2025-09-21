<?php
/**
 * Client gRPC PHP pour tester le service Laptop
 */

require_once 'vendor/autoload.php';

use Mindlet\Pcbook\LaptopServiceClient;
use Mindlet\Pcbook\CreateLaptopRequest;
use Mindlet\Pcbook\CreateLaptopResponse;
use Mindlet\Pcbook\Laptop;
use Mindlet\Pcbook\CPU;
use Mindlet\Pcbook\Memory;
use Mindlet\Pcbook\Memory\Unit as MemoryUnit;
use Mindlet\Pcbook\GPU;
use Mindlet\Pcbook\Storage;
use Mindlet\Pcbook\Storage\Driver as StorageDriver;
use Mindlet\Pcbook\Screen;
use Mindlet\Pcbook\Screen\Resolution as ScreenResolution;
use Mindlet\Pcbook\Screen\Panel as ScreenPanel;
use Mindlet\Pcbook\Keyboard;
use Mindlet\Pcbook\Keyboard\Layout as KeyboardLayout;
use Grpc\ChannelCredentials;
use Google\Protobuf\Timestamp;

function testClient() {
    echo "🚀 Démarrage du client gRPC PHP...\n";
    
    // Création du client gRPC
    $client = new LaptopServiceClient('127.0.0.1:50051', [
        'credentials' => ChannelCredentials::createInsecure(),
        'update_metadata' => function($metadata) {
            $metadata['user-agent'] = ['grpc-php-client/1.0'];
            return $metadata;
        }
    ]);
    
    echo "✅ Client gRPC créé\n";
    
    try {
        // Création du CPU
        $cpu = new CPU();
        $cpu->setBrand('Intel')
            ->setName('Core i7')
            ->setNumberCores(8)
            ->setNumberThreads(16)
            ->setMinGhz(2.6)
            ->setMaxGhz(4.5);
        
        // Création de la mémoire RAM
        $ram = new Memory();
        $ram->setValue(32)
            ->setUnit(MemoryUnit::GIGABYTE);
        
        // Création du GPU
        $gpu = new GPU();
        $gpu->setBrand('NVIDIA')
            ->setName('RTX 4070')
            ->setMinGhz(1.2)
            ->setMaxGhz(2.4);
        
        $gpu_memory = new Memory();
        $gpu_memory->setValue(12)
                   ->setUnit(MemoryUnit::GIGABYTE);
        $gpu->setMemory($gpu_memory);
        
        // Création du stockage
        $storage = new Storage();
        $storage->setDriver(StorageDriver::SSD);
        
        $storage_memory = new Memory();
        $storage_memory->setValue(1)
                       ->setUnit(MemoryUnit::TERABYTE);
        $storage->setMemory($storage_memory);
        
        // Création de l'écran
        $screen = new Screen();
        $screen->setSizeInch(15.6)
               ->setPanel(ScreenPanel::IPS)
               ->setMultitouch(false);
        
        $resolution = new ScreenResolution();
        $resolution->setWidth(1920)
                   ->setHeight(1080);
        $screen->setResolution($resolution);
        
        // Création du clavier
        $keyboard = new Keyboard();
        $keyboard->setLayout(KeyboardLayout::QWERTY)
                 ->setBacklit(true);
        
        // Création du timestamp
        $timestamp = new Timestamp();
        $timestamp->fromDateTime(new DateTime('2023-10-01T12:00:00Z'));
        
        // Création du laptop
        $laptop = new Laptop();
        $laptop->setId('laptop-php-' . uniqid())
               ->setBrand('ASUS')
               ->setName('ROG Strix G15')
               ->setCpu($cpu)
               ->setRam($ram)
               ->setGpus([$gpu])
               ->setStorages([$storage])
               ->setScreen($screen)
               ->setKeyboard($keyboard)
               ->setWeightKg(2.3)
               ->setWeightLb(5.1)
               ->setPriceUsd(1699.99)
               ->setReleaseYear(2023)
               ->setUpdatedAt($timestamp);
        
        echo "📝 Laptop créé avec ID: " . $laptop->getId() . "\n";
        
        // Création de la requête
        $request = new CreateLaptopRequest();
        $request->setLaptop($laptop);
        
        echo "📤 Envoi de la requête au serveur...\n";
        
        // Appel du service
        $call = $client->CreateLaptop($request);
        list($response, $status) = $call->wait();
        
        if ($status->code !== Grpc\STATUS_OK) {
            echo "❌ Erreur gRPC: " . $status->code . " - " . $status->details . "\n";
            return false;
        }
        
        echo "✅ Réponse reçue du serveur!\n";
        echo "🆔 ID du laptop créé: " . $response->getId() . "\n";
        echo "🎉 Test réussi!\n";
        
        return true;
        
    } catch (Exception $e) {
        echo "❌ Exception: " . $e->getMessage() . "\n";
        echo "📍 Trace: " . $e->getTraceAsString() . "\n";
        return false;
    }
}

// Point d'entrée principal
echo "=== Client gRPC PHP pour Mindlet Laptop Service ===\n\n";

if (testClient()) {
    echo "\n🎯 Test terminé avec succès!\n";
    exit(0);
} else {
    echo "\n💥 Test échoué!\n";
    exit(1);
}
?>