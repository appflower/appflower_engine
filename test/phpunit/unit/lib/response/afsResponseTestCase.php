<?php
/**
 * Response test case
 *
 * @package appFlowerStudio
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
class afsResponseTest extends sfBasePhpunitTestCase implements sfPhpunitFixtureFileAggregator
{
    /**
     * Testing exists needed class or not
     * 
     * @author Sergey Startsev
     */
    public function testClassesExisting() 
    {
        $this->assertTrue(class_exists('afResponseBase'), "'afResponseBase' class doesn't exists");
        $this->assertTrue(class_exists('afResponse'), "'afResponse' class doesn't exists");
        $this->assertTrue(class_exists('afResponseHelper'), "'afResponse' class doesn't exists");
    }
    
    /**
     * Testing presentations - json and array
     *
     * @depends testClassesExisting
     * 
     * @author Sergey Startsev
     */
    public function testPresentations() 
    {
        $response = afResponseHelper::create()->success(true)->message('test');
        
        $this->assertInstanceOf('afResponse', $response, "check returned value from creating response, returned value should be instance of 'afResponse'");
        
        $response_array = $response->asArray();
        $this->assertTrue(is_array($response_array), "asArray method should return array");
        $this->assertArrayHasKey(afResponseSuccessDecorator::IDENTIFICATOR, $response_array, 'response should contains success identificator');
        $this->assertArrayHasKey(afResponseMessageDecorator::IDENTIFICATOR, $response_array, 'response should contains message identificator');
        
        $this->assertStringEqualsFile($this->fixture()->getFileOwn('ResponseSuccessMessage.json'), $response->asJson(), "expected another response, please check packing");
    }
    
    /**
     * Test on exception
     * 
     * @depends testClassesExisting
     * @expectedException afResponseException
     * 
     * @author Sergey Startsev
     */
    public function testOnNotExistedDecorator() 
    {
        $response = afResponseHelper::create()->not_existed_decorator('test');
    }
    
}
