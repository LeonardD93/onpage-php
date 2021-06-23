<?php
namespace Test;

use OnPage\Thing;

class MainTest extends \PHPUnit\Framework\TestCase
{
    private \OnPage\Api $api;

    public function setUp(): void
    {
        $this->api = new \OnPage\Api($_ENV['COMPANY'], $_ENV['TOKEN']);
    }

    function testSchemaLoaded()
    {
        $this->assertTrue(mb_strlen($this->api->schema->label) > 0);
    }

    function testGetFirstThing() {
        $cap = $this->api->query('capitoli')->first();
        $this->checkFirstChapter($cap);
    }
    
    function testGetAllThings() {
        $caps = $this->api->query('capitoli')->all();
        $this->checkFirstChapter($caps->first());
        $this->assertSame(23, $caps->count());
    }
    
    function testOnDemandRelations() {
        $thing = $this->api->query('capitoli')->first();
        $this->assertCount(1, $thing->rel('argomenti'));
        $arg = $thing->rel('argomenti')->first();
        $this->assertSame('Architetturale;Domestico;Commerciale;Industriale;Arredamento;', $arg->val('nota10'));
        echo "\n";
        foreach ($thing->rel('argomenti') as $arg) {
            var_dump($arg->val('nota10'));
            echo "\n";
        }
    }

    function testPreloadedThings() {
        
    }

    private function checkFirstChapter(Thing $cap) {
        $this->assertNotNull($cap);
        $this->assertInstanceOf(\OnPage\Thing::class, $cap, 'Cannot pull first chapter');
        $this->assertSame(236826, $cap->id);
        $this->assertSame('Profili alluminio', $cap->val('descrizione')[0]);
    }
    
}