<div class="p-6 bg-dark-600 rounded-lg shadow-md" x-data="formHandler()">
    <!-- Toggle Button to Show/Hide Form -->
    <button class="px-4 py-2 text-gray-500 bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out" @click="toggleForm()">
        <span x-text="open ? 'Close Form' : 'Manage Locations'"></span>
    </button>

    <!-- Form (Add/Edit Location) -->
    <div x-show="open" class="mt-4">
        <form id="locationForm" wire:submit.prevent="saveLocation" @submit="handleSubmit($event)" class="space-y-4">
            <!-- Hidden inputs for editMode and id -->
            <input type="hidden" wire:model.defer="editMode" x-model="editMode">
            <input type="hidden" wire:model.defer="id" x-model="id">

            <div>
                <label for="name" class="block text-sm font-extrabold text-red-600">Location Name</label>
                <input type="text" id="name" wire:model.defer="name" x-ref="nameInput" x-model="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" style="color: red;" placeholder="Enter location name">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="latitude" class="block text-sm font-medium text-red-600">Latitude</label>
                <input type="text" id="latitude" wire:model.defer="latitude" x-ref="latitudeInput" x-model="latitude" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" style="color: red;" placeholder="Enter latitude">
                @error('latitude') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="longitude" class="block text-sm font-medium text-red-600">Longitude</label>
                <input type="text" id="longitude" wire:model.defer="longitude" x-ref="longitudeInput" x-model="longitude" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" style="color: red;" placeholder="Enter longitude">
                @error('longitude') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="px-4 py-2 text-white rounded focus:outline-none focus:ring-2 focus:ring-opacity-50 transition duration-150 ease-in-out"
                :class="editMode ? 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500' : 'bg-green-600 hover:bg-green-700 focus:ring-green-500'">
                <span x-ref="buttonText" x-text="editMode ? 'Update Location' : 'Add Location'"></span>
            </button>
        </form>

        <!-- Debug Message -->
        <p x-show="formSubmitted" class="mt-2 text-green-600">Form has been submitted.</p>
    </div>

    <!-- List of Locations -->
    <div class="mt-6">
        <h2 class="text-lg font-semibold text-gray-800">Subscribed Locations</h2>
        <ul class="space-y-2">
            @foreach($locations as $location)
            <li class="flex justify-between items-center py-2 px-4 border border-gray-300 rounded-md bg-gray-50 mt-5">
                <div class="text-gray-700">
                    <strong class="text-red-600">{{ $location->name }}</strong> ({{ $location->latitude }}, {{ $location->longitude }})
                </div>
                <div class="flex space-x-2">
                    <button @click="editLocation({{ $location->id }}, '{{ $location->name }}', '{{ $location->latitude }}', '{{ $location->longitude }}')" class="px-2 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition duration-150 ease-in-out">Edit</button>
                    <button wire:click="deleteLocation({{ $location->id }})" class="px-2 py-1 text-white bg-red-600 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-150 ease-in-out">Delete</button>
                </div>
            </li>
            @endforeach
        </ul>
    </div>

    <!-- Weather Data Display -->
    <div class="mt-6">
        <h2 class="text-lg font-semibold text-gray-800">Weather Data</h2>
        <ul class="space-y-2">
            @foreach($weatherData as $locationId => $data)
            @if(isset($data['weather']))
            <li class="py-2 px-4 border border-gray-300 rounded-md bg-gray-50 text-gray-600">
                <h3 class="text-md font-semibold">Location ID: {{ $locationId }}</h3>
                <p><strong>City:</strong> {{ $data['weather']['name'] }}</p>
                <p><strong>Temperature:</strong> {{ $data['weather']['main']['temp'] }}°C</p>
                <p><strong>Feels Like:</strong> {{ $data['weather']['main']['feels_like'] }}°C</p>
                <p><strong>Weather Description:</strong> {{ $data['weather']['weather'][0]['description'] }}</p>
                <p><strong>Humidity:</strong> {{ $data['weather']['main']['humidity'] }}%</p>
                <p><strong>Wind Speed:</strong> {{ $data['weather']['wind']['speed'] }} m/s</p>
                <p><strong>Pressure:</strong> {{ $data['weather']['main']['pressure'] }} hPa</p>

                @if(isset($data['hourlyForecast']) && is_array($data['hourlyForecast']))
                <h4 class="text-md font-extrabold mt-10 text-green-400">Hourly Forecast</h4>
                <ul class="space-y-2">
                    <div class="container mx-auto px-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                            @foreach($data['hourlyForecast'] as $hour)
                            @if(is_array($hour) && isset($hour['dt']) && isset($hour['main']['temp']) && isset($hour['weather'][0]['description']))
                            <div class="bg-white shadow-lg rounded-lg p-4 flex flex-col items-start">
                                <p class="text-lg font-semibold mb-2">
                                    <strong>Time:</strong> {{ \Carbon\Carbon::createFromTimestamp($hour['dt'])->format('H:i') }}
                                </p>
                                <p class="text-base mb-2">
                                    <strong>Temperature:</strong> {{ $hour['main']['temp'] }}°C
                                </p>
                                <p class="text-base mb-2">
                                    <strong>Weather:</strong> {{ $hour['weather'][0]['description'] }}
                                </p>
                            </div>
                            @else
                            <div class="bg-white shadow-lg rounded-lg p-4 flex flex-col items-start">
                                <p class="text-base">No valid hourly forecast data.</p>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </ul>
                @else
                <p>No hourly forecast data available.</p>
                @endif
            </li>
            @else
            <li class="py-2 px-4 border border-gray-300 rounded-md bg-gray-50 mt-5">
                <h3 class="text-md font-semibold text-red-500">Location ID: {{ $locationId }}</h3>
                <p class="text-red-500">No weather data available.</p>
            </li>
            @endif
            @endforeach
        </ul>

        <!-- Weather Alerts History -->
        <div class="mt-6">
            <h2 class="text-lg font-semibold text-gray-800">Weather Alerts History</h2>
            <ul class="space-y-2">
                @foreach($alertsHistory as $alert)
                <li class="py-2 px-4 border border-gray-300 rounded-md bg-gray-50 text-gray-500 mt-5">
                    <p><strong>Type:</strong> {{ $alert['type'] }}</p>
                    <p><strong>Message:</strong> {{ $alert['message'] }}</p>
                    <p><strong>URL:</strong> <a href="{{ $alert['url'] }}" target="_blank" class="text-blue-600 hover:underline">{{ $alert['url'] }}</a></p>
                    <p><strong>Created At:</strong> {{ $alert['created_at'] }}</p>
                    <p><strong>Read At:</strong> {{ $alert['read_at'] }}</p>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<script>
    function formHandler() {
        return {
            open: false,
            editMode: false,
            id: null, // Add id property
            name: '',
            latitude: '',
            longitude: '',
            formSubmitted: false,

            toggleForm() {
                this.open = !this.open;
                if (!this.open) {
                    this.resetForm();
                }
            },

            handleSubmit(event) {
                // You can directly access id and editMode here
                // Ensure Livewire properties are set

                this.$wire.set('editMode', this.editMode);
                this.$wire.set('id', this.id);
                this.$wire.call('saveLocation');


            },

            resetForm() {
                this.name = '';
                this.latitude = '';
                this.longitude = '';
                this.editMode = false;
                this.id = null; // Reset id as well
                this.formSubmitted = false;
            },

            editLocation(id, name, latitude, longitude) {
                this.id = id;
                this.name = name;
                this.latitude = latitude;
                this.longitude = longitude;
                this.editMode = true;
                this.open = true;
            },
        }
    }
</script>