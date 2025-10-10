<?php
it('see cars', function () {
    $page = visit('/');
 
    $page->assertSee('/');
});