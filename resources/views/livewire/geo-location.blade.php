<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Colonne gauche : Formulaire de géolocalisation -->
    <div class="space-y-6">
        <h1 class="text-2xl font-bold">Géolocalisation</h1>

        <form wire:submit.prevent="submit">
            <div>
                <label for="ip" class="block text-sm font-medium text-gray-700">Adresse IP :</label>
                <input type="text" id="ip" wire:model="ip"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <button type="submit"
                class="mt-4 w-full px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Soumettre</button>
        </form>

        @if ($location)
            <div class="bg-gray-100 p-4 rounded-md">
                @if (isset($location['timezone']))
                    <p><strong>Timezone:</strong> {{ $location['timezone'] }}</p>
                @endif
                @if (isset($location['ip']))
                    <p><strong>IP:</strong> {{ $location['ip'] }}</p>
                @endif
                @if (isset($location['city']))
                    <p><strong>City:</strong> {{ $location['city'] }}</p>
                @endif
                @if (isset($location['region']))
                    <p><strong>Region:</strong> {{ $location['region'] }}</p>
                @endif
                @if (isset($location['country']))
                    <p><strong>Country:</strong> {{ $location['country'] }}</p>
                @endif
                @if (isset($location['organization_name']))
                    <p><strong>Organization Name:</strong> {{ $location['organization_name'] }}</p>
                @endif
                @if (isset($location['postal']))
                    <p><strong>Postal:</strong> {{ $location['postal'] }}</p>
                @endif
                @if (isset($location['isp']))
                    <p><strong>ISP:</strong> {{ $location['isp'] }}</p>
                @endif
                @if (isset($location['asn']))
                    <p><strong>ASN:</strong> {{ $location['asn'] }}</p>
                @endif
            </div>
        @elseif ($ip && !filter_var($ip, FILTER_VALIDATE_IP))
            <div class="bg-red-100 p-4 rounded-md">
                <p>Adresse IP invalide.</p>
            </div>
        @endif
    </div>

    <!-- Colonne droite : Carte de géolocalisation -->
    <div id="map" class="h-96 rounded-lg overflow-hidden" wire:ignore></div>

</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        let mapElement = document.getElementById('map');
        if (mapElement) {
            let map = L.map('map').setView([0, 0], 2);
            let marker = null;

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            Livewire.on('location-updated', (latitude, longitude) => {
                // Vérifiez que latitude et longitude sont définies et valides
                if (latitude !== null && longitude !== null) {
                    let newLatLng = L.latLng(latitude, longitude);

                    // Si le marqueur existe, mettez à jour sa position
                    if (marker) {
                        marker.setLatLng(newLatLng);
                    } else {
                        // Sinon, créez un nouveau marqueur
                        marker = L.marker(newLatLng).addTo(map)
                            .bindPopup('Adresse IP géolocalisée')
                            .openPopup();
                    }

                    // Ajustez la vue de la carte pour montrer le marqueur
                    map.setView(newLatLng, 12);
                }
            });
        }
    });
</script>
@endpush
