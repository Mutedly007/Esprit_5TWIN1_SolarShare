<?php

namespace Tests\Feature;

use Tests\TestCase;

class LanguageSwitchTest extends TestCase
{
    public function test_can_switch_to_arabic(): void
    {
        $response = $this->get(route('lang.switch', 'ar'));

        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'ar');
    }

    public function test_can_switch_to_english(): void
    {
        $response = $this->get(route('lang.switch', 'en'));

        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'en');
    }

    public function test_can_switch_to_french(): void
    {
        $response = $this->get(route('lang.switch', 'fr'));

        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'fr');
    }
}

