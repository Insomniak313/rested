<?php
namespace AppBundle\Service;

/**
 * Class CurlService
 * @package AppBundle\Service
 */
class CurlService
{
    private $urls;
    private $mh;
    const CURL_DELAY = 100;

    /**
     * CurlService constructor.
     */
    public function __construct()
    {
        $this->urls       = array();
        $this->curl_array = array();
        $this->mh         = curl_multi_init();
    }

    /**
     * Assignation des URLS
     * @param array $urls
     * @return $this
     */
    public function setUrls(array $urls)
    {
        $this->urls = $urls;

        return $this;
    }

    /**
     * Réinitialisation du MultiCurl
     * @return $this
     */
    public function reset()
    {
        $this->urls       = array();
        $this->curl_array = array();
        $this->mh         = curl_multi_init();

        return $this;
    }

    /**
     * Initialisation du MultiCurl
     * @return $this
     */
    public function init()
    {
        foreach ($this->urls as $url)
        {
            $this->curl_array[] = self::createCurl($url);
            curl_multi_add_handle($this->mh, end($this->curl_array));
        }

        return $this;
    }

    /**
     * Creation d'un processus Curl
     * @param $url
     * @return resource
     */
    public static function createCurl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        return $ch;
    }

    /**
     * Fonction permettant
     * @return array
     */
    public function run()
    {
        $running = NULL;
        do
        {
            usleep(self::CURL_DELAY);
            curl_multi_exec($this->mh, $running);
        }
        while ($running > 0);
        $result = array();
        foreach ($this->urls as $i => $url)
        {
            $result[] = array(
                curl_multi_getcontent($this->curl_array[$i]),
                curl_getinfo($this->curl_array[$i]),
                curl_error($this->curl_array[$i])
            );
        }
        foreach ($this->urls as $i => $url) {
            curl_multi_remove_handle($this->mh, $this->curl_array[$i]);
        }
        return $result;
    }
}