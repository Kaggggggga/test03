<?php


class AllTest extends TestCase
{
    protected $host = null;
    protected $shouldPass = [];
    protected $shouldFail = [];
    protected $redirects = [];

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

    }

    public function setUp()
    {
        parent::setUp();
        $domain = config("app.domain");
        $defaultScheme = "http";
        $this->host = config("app.scheme") . "://$domain";

        $this->shouldPass = [
            ["google.com", "1A1exs0r", "$defaultScheme://google.com"],
            ["http://abc.com", "18ZyPffb"],
        ];

        $this->shouldFail = [
            "{$this->host}",
            "{$this->host}/12312",
            "//",
        ];

        $this->redirects = [
            "12345abc" => 404,
        ];
    }


    public function testSubmitPass()
    {
        foreach ($this->shouldPass as $case) {
            list($url, $hash) = $case;
            $testUrl = $url;
            if(isset($case[2])){
                $testUrl = $case[2];
            }
            $this->json("POST", "/submit", ["url" => $url])
                ->seeJson([
                    "url" => $testUrl,
                    "shorten_url" => "$this->host/$hash",
                ]);

            $response = $this->call("GET", "/$hash");
            $this->assertEquals(301, $response->getStatusCode());
            $this->assertEquals($testUrl, $response->headers->get("location"));
        }
    }

    public function testSubmitFail()
    {
        foreach ($this->shouldFail as $url) {
            $response = $this->call("POST", "/submit", [
                "url" => $url,
            ]);
            $this->assertNotEquals(200, $response->getStatusCode());
        }
    }

    public function testRedirect()
    {
        foreach ($this->redirects as $hash) {
            $response = $this->call("GET", "/$hash");
            $this->assertEquals(404, $response->getStatusCode());
        }
    }


}