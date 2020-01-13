<?php
namespace Seeker\iXiGua\Selenium;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Seeker\seleniumServer\InterfaceSelenium;

class iXiGuaSelenium implements InterfaceSelenium
{
    private $webDriver = null;

    private $selenium = 'http://localhost:4444/wd/hub' ;

    /**
     * 初始化
     * analyst constructor.
     */
    public function __construct()
    {

        $options = new ChromeOptions();
        $options->addArguments([
            "--user-agent=Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1",
            '--window-size=414,736',
            '--headless'
        ]);

        $desiredCapabilities = DesiredCapabilities::chrome();
        $desiredCapabilities->setCapability(ChromeOptions::CAPABILITY ,$options) ;

        $this->webDriver = RemoteWebDriver::create( $this->selenium , $desiredCapabilities , 5000);

    }

    /**
     * 开始爬取
     * @param $url
     * @param int $sleep
     * @return \Facebook\WebDriver\Remote\RemoteWebElement[]
     */
    public function get( $url , $sleep=5 ){
        $this->webDriver->get( $url );
        sleep($sleep );
        $elements= $this->webDriver->findElements(WebDriverBy::tagName('source'));
        return $this->findVideoSource( $elements );
    }

    /**
     * 找video 标签
     * @param RemoteWebElement $elements
     * @return array
     */
    private function findVideoSource( $elements ){

        $urls=[];
        foreach ($elements as $item){
            $urls[]=$item->getAttribute('src');
        }
        return $urls;
    }

    /**
     * 析构
     */
    public function __destruct()
    {
        $this->webDriver->close() ;
    }
}
