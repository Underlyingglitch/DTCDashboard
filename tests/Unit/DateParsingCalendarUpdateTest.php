<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class DateParsingCalendarUpdateTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_date_parsing_same_day(): void
    {
        $parsed = \App\Models\CalendarItem::parseDate('2024-01-01', '2024-01-01');

        $this->assertEquals('1 jan. 2024', $parsed);
    }

    public function test_date_parsing_same_month(): void
    {
        $parsed = \App\Models\CalendarItem::parseDate('2024-01-01', '2024-01-02');

        $this->assertEquals('1 t/m 2 jan. 2024', $parsed);
    }

    public function test_date_parsing_same_year(): void
    {
        $parsed = \App\Models\CalendarItem::parseDate('2024-01-01', '2024-02-02');

        $this->assertEquals('1 jan. t/m 2 feb. 2024', $parsed);
    }

    public function test_date_parsing_other(): void
    {
        $parsed = \App\Models\CalendarItem::parseDate('2024-01-01', '2025-02-02');

        $this->assertEquals('1 jan. 2024 t/m 2 feb. 2025', $parsed);
    }
}
