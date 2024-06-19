<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On; 
class GeoLocation extends Component
{
    public $ip;
    public $location;
    public $latitude;
    public $longitude;

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
        }
    }

    public function submit()
    {
        $this->fetchLocation();
    }

    public function render()
    {
        return view('livewire.geo-location');
    }
}