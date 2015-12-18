<?php

class AssertTest extends \phpunit_framework_testcase
{
    public static function langProvider()
    {
        return array(array('json'), array('php'), array('ses'));
    }
    public static function provider()
    {
        $data = array();
        foreach (self::langProvider() as $format) {
            foreach (glob(__DIR__ . "/assert/*/*") as $file) {
                $data[] = array($format[0], $file, basename(dirname($file)));
            }
        }
        return $data;
    }

    /**
     *  @dataProvider provider
     */
    public function testAll($format, $file, $expected)
    {
        $detect = LanguageDetector\Detect::initByPath(__DIR__."/../example/datafile.{$format}");
        $lang = $detect->detect(file_get_contents($file));
        if (is_array($lang)) {
            $this->assertEquals($expected, $lang[0]['lang']);
            return;
        }
        $this->assertEquals($expected, $lang);
    }

    /**
     *  @dataProvider langProvider
     */
    public function testGetLanguages($format)
    {
        $detect = LanguageDetector\Detect::initByPath(__DIR__."/../example/datafile." . $format);
        $langs = $detect->getLanguages();
        $this->assertTrue(is_array($langs));
        $this->assertTrue(count($langs) > 10);
    }
}

