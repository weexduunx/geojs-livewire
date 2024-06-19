<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On; 
use GuzzleHttp\Client;

class GeoLocation extends Component
{
    public $ip;
    public $location;
    public $latitude;
    public $longitude;
    public $address;
    public function fetchLocation()
    {
        if (filter_var($this->ip, FILTER_VALIDATE_IP)) {
            $response = Http::withOptions(['verify' => false])->get("https://get.geojs.io/v1/ip/geo/{$this->ip}.json");
            
            if ($response->successful()) {
                $this->location = $response->json();
                
                // Assign latitude and longitude
                $this->latitude = $this->location['latitude'] ?? null;
                $this->longitude = $this->location['longitude'] ?? null;
                
                // Dispatch Livewire event with latitude and longitude
                $this->dispatch('location-updated', $this->latitude, $this->longitude);
            } else {
                $this->location = null;
                $this->latitude = null;
                $this->longitude = null;
            }
        } else {
            $this->location = null;
            $this->latitude = null;
            $this->longitude = null;
            $this->addError('ip', 'Erreur lors de la récupération de la géolocalisation.');
        }

    
    }
    public function fetchLocationByAddress($address)
    {
        $response = Http::withOptions(['verify' => false])->withHeaders([
            'x-rapidapi-host' => 'geocode-address-to-location.p.rapidapi.com',
            'x-rapidapi-key' => '89d0a57d18msh2ef5e1739b44b3dp134042jsndb9b2ce7e901',
        ])->get('https://geocode-address-to-location.p.rapidapi.com/v1/geocode/autocomplete', [
            'text' => $address,
            'type' => 'city',
            'lon' => 13.38,
            'lat' => 52.51,
            'limit' => 1,
            'countrycodes' => 'de',
            'lang' => 'fr',
        ]);

        if ($response->successful()) {
            $result = $response->json();
            if (!empty($result['features'])) {
                $coordinates = $result['features'][0]['geometry']['coordinates'];
                $this->latitude = $coordinates[1];
                $this->longitude = $coordinates[0];
                $this->dispatch('location-updated', $this->latitude, $this->longitude);
            } else {
                $this->addError('address', 'Adresse physique non trouvée.');
            }
        } else {
            $this->addError('address', 'Erreur lors de la récupération de la géolocalisation par adresse physique.');
        }
    }
    // public function fetchLocationByAddress($address)
    // {
    //     $response = Http::withHeaders([
    //         'x-rapidapi-host' => 'geocode-address-to-location.p.rapidapi.com',
    //         'x-rapidapi-key' => '89d0a57d18msh2ef5e1739b44b3dp134042jsndb9b2ce7e901',
    //     ])->get('https://geocode-address-to-location.p.rapidapi.com/v1/geocode/autocomplete', [
    //         'text' => $address,
    //         'type' => 'city',
    //         'lon' => 13.38,
    //         'lat' => 52.51,
    //         'limit' => 1,
    //         'countrycodes' => 'de',
    //         'lang' => 'fr',
    //     ]);

    //     if ($response->successful()) {
    //         $result = $response->json();
    //         if (!empty($result['features'])) {
    //             $coordinates = $result['features'][0]['geometry']['coordinates'];
    //             $this->latitude = $coordinates[1];
    //             $this->longitude = $coordinates[0];
    //             $this->dispatch('location-updated',$this->latitude, $this->longitude);
    //         } else {
    //             $this->addError('address', 'Adresse physique non trouvée.');
    //         }
    //     } else {
    //         $this->addError('address', 'Erreur lors de la récupération de la géolocalisation par adresse physique.');
    //     }
    // }

    public function submit()
    {
        // Vérifier si l'utilisateur a saisi une adresse IP ou une adresse physique
        if ($this->ip && filter_var($this->ip, FILTER_VALIDATE_IP)) {
            // Si c'est une adresse IP valide, appeler fetchLocation avec l'adresse IP
            $this->fetchLocation($this->ip);
        } elseif ($this->address) {
            // Si c'est une adresse physique, appeler fetchLocation avec l'adresse physique
            $this->fetchLocationByAddress($this->address);
        } else {
            // Afficher un message d'erreur si aucune adresse n'est saisie
            $this->addError('ip', 'Veuillez saisir une adresse IP valide ou une adresse physique.');
        }
    }
    // public function submit()
    // {
    //     $this->fetchLocation();
    // }
    // public function fetchHospitals()
    // {
    //     $client = new Client();

    //     try {
    //         $response = $client->request('GET',  'https://senegal-api.p.rapidapi.com/geofy?address=Dakar', [
    //             'headers' => [
    //                 'x-rapidapi-host' => 'senegal-api.p.rapidapi.com',
    //                 'x-rapidapi-key' => '89d0a57d18msh2ef5e1739b44b3dp134042jsndb9b2ce7e901',
    //             ],
    //             // 'query' => [
    //             //     'type' => 'hospital',
    //             //     'region' => 'Dakar',
    //             //     'city' => 'Touba',
    //             //     'limit' => 10,
    //             // ],
    //             'verify' => false, // Désactiver la vérification du certificat SSL
    //         ]);

    //         $body = $response->getBody();
    //         $data = json_decode($body, true);

    //         // Traitez les données ici, par exemple, stockez-les dans une propriété pour les afficher dans votre vue
    //         $this->hospitals = $data;

    //         // Exemple de traitement pour l'affichage dans la vue
    //         $this->dispatch('hospitals-updated', [
    //             'hospitals' => $data,
    //         ]);

    //     } catch (\Exception $e) {
    //         // Gestion des erreurs
    //         dd($e->getMessage()); // Affiche l'erreur, à adapter selon votre besoin
    //     }
    // }

    public function render()
    {
        return view('livewire.geo-location');
    }
}