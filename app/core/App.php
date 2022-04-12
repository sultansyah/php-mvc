<?php

class App
{
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    function __construct()
    {
        $url = $this->parseURL();

        // mengecek apakah controller yang di akses null atau bukan, jika null kasih nilai Home jika tidak ambil controllernya
        $url = ($url == NULL) ? $url = [$this->controller] : $url;

        // untuk mengecek apakah controllersnya ada diakses atau enggak, jika ada ambil, jika tidak berarti defaultnya adalah Home
        if (file_exists('../app/controllers/' . $url[0] . '.php')) {
            $this->controller = $url[0];
            unset($url[0]);
        }
        // mengambil controllers sesuai controllers yang ada di alamat, jika tidak ada maka defaultnya adalah controllers home
        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // mengecek method apa yang ada di alamat, jika tidak ada maka defaultnya adalah method index
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // mengecek apakah ada parameter di alamat
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // jalankan controller dan method, serta kirimkan params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // function untuk mengambil alamat saat ini
    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/'); // menghapus "/" di akhir url
            $url = filter_var($url, FILTER_SANITIZE_URL); // menfilter dari kode yang berpontensi hack
            $url = explode('/', $url); // memecah string yg terpisah dari '/' menjadi array
            return $url;
        }
    }
}