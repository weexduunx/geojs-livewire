{{-- <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Colonne gauche : Formulaire -->
    <div>
        <h1 class="text-2xl font-bold mb-4">Géolocalisation</h1>

        <!-- Formulaire de Géolocalisation par Adresse IP -->
        <form wire:submit.prevent="submitIp" class="mb-6">
            <div class="mb-4">
                <label for="ip" class="block text-sm font-medium text-gray-700">Adresse IP :</label>
                <input type="text" id="ip" wire:model="ip"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <button type="submit"
                class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Géolocaliser par IP</button>
        </form>

        <!-- Formulaire de Géolocalisation par Adresse Physique -->
        <form wire:submit.prevent="submitAddress" class="mb-6">
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Adresse physique :</label>
                <input type="text" id="address" wire:model="address"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <button type="submit"
                class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Géolocaliser par Adresse</button>
        </form>

        <!-- Messages d'erreur -->
        @if ($errors->any())
            <div class="bg-red-100 p-4 rounded-md mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-sm text-red-700">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Colonne droite : Résultats et Carte -->
    <div>
        <!-- Affichage des résultats de géolocalisation -->
        @if ($location)
            <div class="bg-gray-100 p-4 rounded-md mb-6">
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
        @elseif($ip && !filter_var($ip, FILTER_VALIDATE_IP))
            <div class="bg-red-100 p-4 rounded-md mb-6">
                <p>Adresse IP invalide.</p>
            </div>
        @elseif($address && $errors->has('address'))
            <div class="bg-red-100 p-4 rounded-md mb-6">
                <p>Adresse physique non trouvée.</p>
            </div>
        @endif

        <!-- Carte -->
        <div id="map" style="height: 400px;" wire:ignore></div>
    </div>
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
@endpush --}}
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Colonne gauche : Formulaire -->
    <div>
        <h1 class="text-2xl font-bold mb-4">Géolocalisation</h1>

        <!-- Formulaire de Géolocalisation par Adresse IP -->
        <form wire:submit.prevent="submitIp" class="mb-6">
            <div class="mb-4">
                <label for="ip" class="block text-sm font-medium text-gray-700">Adresse IP :</label>
                <input type="text" id="ip" wire:model="ip"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <button type="submit"
                class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Géolocaliser par IP</button>
        </form>

        <!-- Formulaire de Géolocalisation par Adresse Physique -->
        <form wire:submit.prevent="submitAddress" class="mb-6">
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Adresse physique :</label>
                <input type="text" id="address" wire:model="address"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <button type="submit"
                class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Géolocaliser par Adresse</button>
        </form>

        <!-- Messages d'erreur -->
        @if ($errors->any())
            <div class="bg-red-100 p-4 rounded-md mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-sm text-red-700">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Colonne droite : Résultats et Carte -->
    <div>
        <!-- Affichage des résultats de géolocalisation -->
        @if ($location)
            <div class="bg-gray-100 p-4 rounded-md mb-6">
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
        @elseif($ip && !filter_var($ip, FILTER_VALIDATE_IP))
            <div class="bg-red-100 p-4 rounded-md mb-6">
                <p>Adresse IP invalide.</p>
            </div>
        @elseif($address && $errors->has('address'))
            <div class="bg-red-100 p-4 rounded-md mb-6">
                <p>Adresse physique non trouvée.</p>
            </div>
        @endif

        <!-- Carte -->
        <div id="map" style="height: 400px;" wire:ignore></div>
    </div>
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
                            .bindPopup('Adresse géolocalisée')
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
