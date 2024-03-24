<?php
class Ve {
    private $capitalRank = ["primary","admin","minor"];
    private $fileName = "ve.json";
    private $ve;
    public function __construct() {
        $this->ve = json_decode(json: file_get_contents(filename: $this->fileName),associative: true);
    }
    public function getVe() : array {
        return $this->ve;
    }
    public function states() : array {
        $states = array_unique(array_column($this->ve, 'admin_name'));
        sort($states);
        return $states;
    }
    public function getCity(string $city) : array|null {
        foreach ($this->ve as $value) {
            $city_arr = $value["city"];
            if($city === $city_arr) return $value;
        }
        return null;
    }
    public function cities() : array {
        $cities = [];
        $cities = array_column($this->ve, "city");
        sort($cities);
        return $cities;
    }
    public function citiesByState(string $state) : array {
        $cities = [];
        foreach ($this->ve as $indx => $value) {
            if($value["admin_name"] !== $state) continue;
            $cities[$indx] = $value["city"];
        }
        sort($cities);
        return $cities;
    }
}
?>