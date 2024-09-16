<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use App\Services\WeatherService;

class ManageLocationsComponent extends Component
{
    public $locations = [];
    public $name = '';
    public $latitude = '';
    public $longitude = '';
    public $editMode = false;
    public $locationId = null;
    public $weatherData = [];
    public $alerts = [];
    public $alertsHistory = [];
    public $id = null;



    public function mount()
    {

        $this->loadLocations();
        $this->loadWeatherData();
        $this->loadWeatherAlertsHistory();
        //  $this->saveLocation();
    }

    public function addLocation()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        Location::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        $this->resetFormFields();
    }

    public function editLocation($locationId)
    {
        $location = Location::findOrFail($locationId);
        $this->locationId = $locationId;
        $this->name = $location->name;
        $this->latitude = $location->latitude;
        $this->longitude = $location->longitude;
        $this->editMode = true;
    }

    public function updateLocation($id)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $location = Location::findOrFail($id);

        $location->update([
            'name' => $this->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        $this->resetFormFields();
    }



    public function deleteLocation($locationId)
    {
        try {
            $location = Location::findOrFail($locationId);
            $location->delete();
            $this->loadLocations();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to delete location.'], 500);
        }
    }

    private function resetFormFields()
    {
        $this->name = '';
        $this->latitude = '';
        $this->longitude = '';
        $this->editMode = false;
        $this->locationId = null;

        $this->loadLocations();
    }

    private function loadLocations()
    {
        $this->locations = Auth::user()->locations;
    }

    public function saveLocation()
    {

        if ($this->editMode) {

            $this->updateLocation($this->id);
        } else {
            $this->addLocation();
        }
        $this->resetFormFields();
    
    }



    public function render()
    {
        return view('livewire.manage-locations-component');
    }

    public function getWeatherForLocation($locationName)
    {
        $weather = null;
        $hourlyForecast = null;
        $error = null;

        try {
            $weatherService = app(WeatherService::class);
            $weather = $weatherService->getCurrentWeather($locationName);
            $hourlyForecastResponse = $weatherService->getHourlyForecast($locationName);


            $hourlyForecast = $hourlyForecastResponse['list'] ?? [];
        } catch (\Exception $e) {
            $error = 'Error fetching weather data: ' . $e->getMessage();
        }

        return [
            'weather' => $weather,
            'hourlyForecast' => $hourlyForecast,
            'error' => $error,
        ];
    }



    public function loadWeatherData()
    {
        foreach ($this->locations as $location) {
            $this->weatherData[$location->id] = $this->getWeatherForLocation($location->name);
        }
    }
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications;

        return view('notifications.index', compact('notifications'));
    }
    public function loadWeatherAlertsHistory()
    {

        $user = Auth::user();
        $notifications = $user->notifications;


        $this->alertsHistory = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'message' => $notification->data['message'] ?? 'No message available',
                'url' => $notification->data['url'] ?? '#',
                'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                'read_at' => $notification->read_at ? $notification->read_at->format('Y-m-d H:i:s') : 'Not read',
            ];
        });
    }
}
